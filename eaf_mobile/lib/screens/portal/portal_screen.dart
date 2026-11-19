import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:webview_flutter_android/webview_flutter_android.dart';
import 'package:webview_flutter_wkwebview/webview_flutter_wkwebview.dart';

/// EAF Connect Live Web Portal Screen
/// Loads https://eafconnect.com directly with:
/// • 100% real-time data sync with live Hostinger website
/// • Persistent sessions (CodeIgniter session cookies maintained)
/// • Native pull-to-refresh
/// • Sleek top loading progress indicator
/// • Android back button web history navigation
/// • Deep link routing for WhatsApp, phone calls (tel:), and emails (mailto:)
/// • Native bottom navigation bar with active tab sync
/// • Clean native offline fallback screen
class PortalScreen extends StatefulWidget {
  final String initialUrl;
  const PortalScreen({
    super.key,
    this.initialUrl = 'https://eafconnect.com/member/dashboard',
  });

  @override
  State<PortalScreen> createState() => _PortalScreenState();
}

class _PortalScreenState extends State<PortalScreen> {
  late final WebViewController _controller;
  double _loadingProgress = 0.0;
  bool _isLoading = true;
  bool _hasError = false;
  String _errorMessage = '';
  String _currentUrl = '';
  int _currentTabIndex = 0;
  DateTime? _lastBackPress;

  // Primary live portal tabs
  static const String _dashboardUrl = 'https://eafconnect.com/member/dashboard';
  static const String _connectsUrl  = 'https://eafconnect.com/member/connect';
  static const String _mitbUrl      = 'https://eafconnect.com/member/mitb';
  static const String _f2fUrl       = 'https://eafconnect.com/member/f2f';
  static const String _profileUrl   = 'https://eafconnect.com/member/profile';

  bool get _isMemberPortal {
    final lower = _currentUrl.toLowerCase();
    return lower.contains('/member/') && !lower.contains('login');
  }

  @override
  void initState() {
    super.initState();
    _currentUrl = widget.initialUrl;
    _initWebViewController();
  }

  void _initWebViewController() {
    late final PlatformWebViewControllerCreationParams params;
    if (WebViewPlatform.instance is WebKitWebViewPlatform) {
      params = WebKitWebViewControllerCreationParams(
        allowsInlineMediaPlayback: true,
        mediaTypesRequiringUserAction: const <PlaybackMediaTypes>{},
      );
    } else {
      params = const PlatformWebViewControllerCreationParams();
    }

    final WebViewController controller =
        WebViewController.fromPlatformCreationParams(params);

    if (controller.platform is AndroidWebViewController) {
      AndroidWebViewController.enableDebugging(false);
      (controller.platform as AndroidWebViewController)
          .setMediaPlaybackRequiresUserGesture(false);
    }

    controller
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0xFF101B35))
      ..setNavigationDelegate(
        NavigationDelegate(
          onProgress: (int progress) {
            if (mounted) {
              setState(() {
                _loadingProgress = progress / 100.0;
              });
            }
          },
          onPageStarted: (String url) {
            if (mounted) {
              setState(() {
                _currentUrl = url;
                _isLoading = true;
                _hasError = false;
              });
            }
          },
          onPageFinished: (String url) {
            if (mounted) {
              setState(() {
                _currentUrl = url;
                _isLoading = false;
              });
              _syncCurrentTab(url);
            }
          },
          onWebResourceError: (WebResourceError error) {
            if (error.isForMainFrame == true && mounted) {
              setState(() {
                _hasError = true;
                _isLoading = false;
                _errorMessage = error.description;
              });
            }
          },
          onNavigationRequest: (NavigationRequest request) async {
            final url = request.url;

            // Handle external schemes: tel, mailto, whatsapp, sms
            if (url.startsWith('tel:') ||
                url.startsWith('mailto:') ||
                url.startsWith('sms:') ||
                url.startsWith('whatsapp:') ||
                url.contains('wa.me/')) {
              try {
                final uri = Uri.parse(url);
                if (await canLaunchUrl(uri)) {
                  await launchUrl(uri, mode: LaunchMode.externalApplication);
                }
              } catch (_) {}
              return NavigationDecision.prevent;
            }

            return NavigationDecision.navigate;
          },
        ),
      )
      ..loadRequest(Uri.parse(widget.initialUrl));

    _controller = controller;
  }

  void _syncCurrentTab(String url) {
    final lower = url.toLowerCase();
    int newIndex = _currentTabIndex;

    if (lower.contains('/member/dashboard')) {
      newIndex = 0;
    } else if (lower.contains('/member/connect')) {
      newIndex = 1;
    } else if (lower.contains('/member/mitb')) {
      newIndex = 2;
    } else if (lower.contains('/member/f2f') || lower.contains('/member/chapter_meetings')) {
      newIndex = 3;
    } else if (lower.contains('/member/profile')) {
      newIndex = 4;
    }

    if (newIndex != _currentTabIndex && mounted) {
      setState(() => _currentTabIndex = newIndex);
    }
  }

  void _onTabTapped(int index) {
    setState(() => _currentTabIndex = index);
    const urls = [_dashboardUrl, _connectsUrl, _mitbUrl, _f2fUrl, _profileUrl];
    if (index >= 0 && index < urls.length) {
      _controller.loadRequest(Uri.parse(urls[index]));
    }
  }

  Future<void> _handlePopScope(bool didPop, dynamic result) async {
    if (didPop) return;

    if (await _controller.canGoBack()) {
      await _controller.goBack();
      return;
    }

    final now = DateTime.now();
    if (_lastBackPress == null || now.difference(_lastBackPress!) > const Duration(seconds: 2)) {
      _lastBackPress = now;
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Press back again to exit EAF Connect'),
            duration: Duration(seconds: 2),
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    } else {
      SystemNavigator.pop();
    }
  }

  @override
  Widget build(BuildContext context) {
    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: const SystemUiOverlayStyle(
        statusBarColor: Color(0xFF101B35),
        statusBarIconBrightness: Brightness.light,
        systemNavigationBarColor: Color(0xFF101B35),
        systemNavigationBarIconBrightness: Brightness.light,
      ),
      child: PopScope(
        canPop: false,
        onPopInvokedWithResult: _handlePopScope,
        child: Scaffold(
          backgroundColor: const Color(0xFF101B35),
          body: SafeArea(
            bottom: !_isMemberPortal,
            child: Column(
              children: [
                // Top progress indicator
                if (_isLoading)
                  LinearProgressIndicator(
                    value: _loadingProgress > 0 ? _loadingProgress : null,
                    minHeight: 3,
                    backgroundColor: const Color(0xFF101B35),
                    valueColor: const AlwaysStoppedAnimation<Color>(Color(0xFFC6A667)),
                  ),

                // Main body: WebView or Offline Error
                Expanded(
                  child: _hasError
                      ? _buildOfflineScreen()
                      : RefreshIndicator(
                          color: const Color(0xFFC6A667),
                          backgroundColor: const Color(0xFF101B35),
                          onRefresh: () async {
                            await _controller.reload();
                          },
                          child: WebViewWidget(controller: _controller),
                        ),
                ),
              ],
            ),
          ),

          // Native Bottom Navigation Bar (active only when logged in on member pages)
          bottomNavigationBar: _isMemberPortal ? _buildBottomNavBar() : null,
        ),
      ),
    );
  }

  Widget _buildBottomNavBar() {
    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFF101B35),
        border: Border(
          top: BorderSide(color: Colors.white.withValues(alpha: 0.1), width: 1),
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.3),
            blurRadius: 10,
            offset: const Offset(0, -3),
          ),
        ],
      ),
      child: NavigationBar(
        selectedIndex: _currentTabIndex,
        onDestinationSelected: _onTabTapped,
        backgroundColor: const Color(0xFF101B35),
        indicatorColor: const Color(0xFFC6A667).withValues(alpha: 0.2),
        elevation: 0,
        height: 64,
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.dashboard_outlined, color: Colors.white70),
            selectedIcon: Icon(Icons.dashboard_rounded, color: Color(0xFFC6A667)),
            label: 'Home',
          ),
          NavigationDestination(
            icon: Icon(Icons.handshake_outlined, color: Colors.white70),
            selectedIcon: Icon(Icons.handshake_rounded, color: Color(0xFFC6A667)),
            label: 'Connects',
          ),
          NavigationDestination(
            icon: Icon(Icons.currency_rupee_outlined, color: Colors.white70),
            selectedIcon: Icon(Icons.currency_rupee_rounded, color: Color(0xFFC6A667)),
            label: 'MITB',
          ),
          NavigationDestination(
            icon: Icon(Icons.people_alt_outlined, color: Colors.white70),
            selectedIcon: Icon(Icons.people_alt_rounded, color: Color(0xFFC6A667)),
            label: 'F2F',
          ),
          NavigationDestination(
            icon: Icon(Icons.person_outline_rounded, color: Colors.white70),
            selectedIcon: Icon(Icons.person_rounded, color: Color(0xFFC6A667)),
            label: 'Profile',
          ),
        ],
      ),
    );
  }

  Widget _buildOfflineScreen() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 32),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 80,
              height: 80,
              decoration: BoxDecoration(
                color: const Color(0xFF172B50),
                shape: BoxShape.circle,
                border: Border.all(color: const Color(0xFFC6A667).withValues(alpha: 0.4), width: 2),
              ),
              child: const Icon(Icons.wifi_off_rounded, size: 40, color: Color(0xFFC6A667)),
            ),
            const SizedBox(height: 24),
            const Text(
              'No Connection',
              style: TextStyle(
                color: Colors.white,
                fontSize: 22,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 10),
            Text(
              _errorMessage.isNotEmpty
                  ? _errorMessage
                  : 'Unable to reach https://eafconnect.com.\nPlease check your mobile data or Wi-Fi connection.',
              textAlign: TextAlign.center,
              style: const TextStyle(
                color: Colors.white70,
                fontSize: 14,
                height: 1.5,
              ),
            ),
            const SizedBox(height: 28),
            ElevatedButton.icon(
              onPressed: () {
                setState(() {
                  _hasError = false;
                  _isLoading = true;
                });
                _controller.reload();
              },
              icon: const Icon(Icons.refresh_rounded, color: Color(0xFF101B35)),
              label: const Text(
                'Retry Connection',
                style: TextStyle(
                  color: Color(0xFF101B35),
                  fontWeight: FontWeight.w700,
                  fontSize: 15,
                ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFFC6A667),
                padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

