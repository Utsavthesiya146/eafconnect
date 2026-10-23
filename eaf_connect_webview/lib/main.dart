import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:webview_flutter/webview_flutter.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Set system UI overlay style
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
      statusBarBrightness: Brightness.light,
    ),
  );

  runApp(const EafConnectApp());
}

class EafConnectApp extends StatelessWidget {
  const EafConnectApp({super.key});

  static const String appTitle = 'EAF Connect';
  static const Color primaryColor = Color(0xFF0F3B68);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: appTitle,
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: primaryColor,
          primary: primaryColor,
        ),
        useMaterial3: true,
        scaffoldBackgroundColor: Colors.white,
      ),
      home: const WebViewScreen(),
    );
  }
}

class WebViewScreen extends StatefulWidget {
  const WebViewScreen({super.key});

  static const String initialUrl = 'https://eafconnect.com/';
  static const String allowedHost = 'eafconnect.com';

  @override
  State<WebViewScreen> createState() => _WebViewScreenState();
}

class _WebViewScreenState extends State<WebViewScreen> {
  late final WebViewController _controller;
  int _progress = 0;
  bool _isLoading = true;
  bool _hasError = false;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
    _initWebViewController();
  }

  void _initWebViewController() {
    final controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(Colors.white)
      ..setNavigationDelegate(
        NavigationDelegate(
          onProgress: (int progress) {
            setState(() {
              _progress = progress;
            });
          },
          onPageStarted: (String url) {
            setState(() {
              _hasError = false;
              _errorMessage = '';
            });
          },
          onPageFinished: (String url) {
            setState(() {
              _isLoading = false;
              _progress = 100;
            });
          },
          onWebResourceError: (WebResourceError error) {
            // Only flag main frame errors to avoid blocking minor resource loading
            if (error.isForMainFrame ?? true) {
              setState(() {
                _hasError = true;
                _errorMessage = error.description;
              });
            }
          },
          onNavigationRequest: (NavigationRequest request) async {
            final uri = Uri.tryParse(request.url);
            if (uri == null) return NavigationDecision.prevent;

            final scheme = uri.scheme.toLowerCase();
            final host = uri.host.toLowerCase();

            // Handle non-HTTP schemes (tel, mailto, whatsapp, sms)
            if (scheme == 'tel' ||
                scheme == 'mailto' ||
                scheme == 'sms' ||
                scheme == 'whatsapp' ||
                scheme == 'intent') {
              try {
                await launchUrl(uri, mode: LaunchMode.externalApplication);
              } catch (e) {
                if (kDebugMode) {
                  print('Could not launch URI: $uri ($e)');
                }
              }
              return NavigationDecision.prevent;
            }

            // Check if domain belongs to EAF Connect
            if (host.endsWith(WebViewScreen.allowedHost)) {
              return NavigationDecision.navigate;
            }

            // External web links open in external browser
            try {
              await launchUrl(uri, mode: LaunchMode.externalApplication);
            } catch (e) {
              if (kDebugMode) {
                print('Could not launch external URL: $uri ($e)');
              }
            }
            return NavigationDecision.prevent;
          },
        ),
      );

    _controller = controller;
    _controller.loadRequest(Uri.parse(WebViewScreen.initialUrl));
  }

  Future<void> _handleRefresh() async {
    setState(() {
      _hasError = false;
    });
    await _controller.reload();
  }

  Future<void> _retryLoading() async {
    setState(() {
      _hasError = false;
      _isLoading = true;
    });
    await _controller.loadRequest(Uri.parse(WebViewScreen.initialUrl));
  }

  @override
  Widget build(BuildContext context) {
    return PopScope(
      canPop: false,
      onPopInvokedWithResult: (didPop, result) async {
        if (didPop) return;

        final canGoBack = await _controller.canGoBack();
        if (canGoBack) {
          await _controller.goBack();
        } else {
          if (!context.mounted) return;
          final shouldExit = await showDialog<bool>(
            context: context,
            builder: (ctx) => AlertDialog(
              title: const Text('Exit App'),
              content: const Text('Are you sure you want to exit EAF Connect?'),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(ctx).pop(false),
                  child: const Text('Cancel'),
                ),
                FilledButton(
                  onPressed: () => Navigator.of(ctx).pop(true),
                  child: const Text('Exit'),
                ),
              ],
            ),
          );

          if (shouldExit == true) {
            SystemNavigator.pop();
          }
        }
      },
      child: Scaffold(
        backgroundColor: Colors.white,
        body: SafeArea(
          child: Stack(
            children: [
              // Main WebView with Pull to Refresh
              if (!_hasError)
                RefreshIndicator(
                  color: EafConnectApp.primaryColor,
                  onRefresh: _handleRefresh,
                  child: WebViewWidget(controller: _controller),
                ),

              // Progress Bar at the top
              if (_progress < 100 && !_hasError)
                Positioned(
                  top: 0,
                  left: 0,
                  right: 0,
                  child: LinearProgressIndicator(
                    value: _progress / 100.0,
                    backgroundColor: Colors.transparent,
                    valueColor: const AlwaysStoppedAnimation<Color>(
                      EafConnectApp.primaryColor,
                    ),
                    minHeight: 3,
                  ),
                ),

              // Error / Offline Screen
              if (_hasError)
                Center(
                  child: Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 32),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Image.asset(
                          'assets/images/logo.jpg',
                          width: 140,
                          height: 140,
                          errorBuilder: (context, error, stackTrace) => const Icon(
                            Icons.wifi_off_rounded,
                            size: 80,
                            color: EafConnectApp.primaryColor,
                          ),
                        ),
                        const SizedBox(height: 24),
                        const Text(
                          'Connection Error',
                          style: TextStyle(
                            fontSize: 22,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF1E293B),
                          ),
                        ),
                        const SizedBox(height: 12),
                        Text(
                          _errorMessage.isNotEmpty
                              ? _errorMessage
                              : 'Unable to connect to EAF Connect servers. Please check your internet connection.',
                          textAlign: TextAlign.center,
                          style: const TextStyle(
                            fontSize: 14,
                            color: Color(0xFF64748B),
                          ),
                        ),
                        const SizedBox(height: 28),
                        FilledButton.icon(
                          onPressed: _retryLoading,
                          style: FilledButton.styleFrom(
                            backgroundColor: EafConnectApp.primaryColor,
                            padding: const EdgeInsets.symmetric(
                              horizontal: 28,
                              vertical: 14,
                            ),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                          icon: const Icon(Icons.refresh_rounded),
                          label: const Text(
                            'Try Again',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

              // Initial Splash Loader overlay
              if (_isLoading && !_hasError)
                Container(
                  color: Colors.white,
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(20),
                          child: Image.asset(
                            'assets/images/logo.jpg',
                            width: 130,
                            height: 130,
                            errorBuilder: (context, error, stackTrace) => const Icon(
                              Icons.business_center_rounded,
                              size: 80,
                              color: EafConnectApp.primaryColor,
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),
                        const Text(
                          'EAF Connect',
                          style: TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                            color: EafConnectApp.primaryColor,
                            letterSpacing: 0.5,
                          ),
                        ),
                        const SizedBox(height: 24),
                        const SizedBox(
                          width: 32,
                          height: 32,
                          child: CircularProgressIndicator(
                            strokeWidth: 3,
                            valueColor: AlwaysStoppedAnimation<Color>(
                              EafConnectApp.primaryColor,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }
}
