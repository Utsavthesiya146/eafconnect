import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'core/auth/auth_provider.dart';
import 'screens/splash/splash_screen.dart';
import 'screens/login/login_screen.dart';
import 'screens/dashboard/dashboard_screen.dart';
import 'screens/profile/profile_screen.dart';
import 'screens/connect/connect_screen.dart';
import 'screens/mitb/mitb_screen.dart';
import 'screens/f2f/f2f_screen.dart';
import 'screens/meetings/meetings_screen.dart';
import 'screens/visitors/visitors_screen.dart';
import 'screens/notifications/notifications_screen.dart';
import 'screens/chapter/chapter_screen.dart';
import 'screens/broadcast/broadcast_screen.dart';
import 'screens/invitations/invitations_screen.dart';
import 'screens/reports/reports_screen.dart';

GoRouter createRouter(AuthProvider authProvider) {
  return GoRouter(
    initialLocation: '/splash',
    refreshListenable: authProvider,
    redirect: (context, state) {
      final isLoggedIn = authProvider.isLoggedIn;
      final isLoading  = authProvider.isLoading;
      final path       = state.uri.path;

      if (isLoading && path == '/splash') return null;
      if (path == '/splash') return null;
      if (!isLoggedIn && path != '/login') return '/login';
      if (isLoggedIn  && path == '/login') return '/dashboard';
      return null;
    },
    routes: [
      GoRoute(path: '/splash', builder: (_, s) => const SplashScreen()),
      GoRoute(path: '/login',  builder: (_, s) => const LoginScreen()),

      // All authenticated screens use the bottom nav shell
      ShellRoute(
        builder: (context, state, child) => _AppShell(state: state, child: child),
        routes: [
          GoRoute(path: '/dashboard',     builder: (_, s) => const DashboardScreen()),
          GoRoute(path: '/connect',       builder: (_, s) => const ConnectScreen()),
          GoRoute(path: '/mitb',          builder: (_, s) => const MitbScreen()),
          GoRoute(path: '/notifications', builder: (_, s) => const NotificationsScreen()),
          GoRoute(path: '/profile',       builder: (_, s) => const ProfileScreen()),
          GoRoute(path: '/f2f',           builder: (_, s) => const F2fScreen()),
          GoRoute(path: '/meetings',      builder: (_, s) => const MeetingsScreen()),
          GoRoute(path: '/visitors',      builder: (_, s) => const VisitorsScreen()),
          GoRoute(path: '/chapter',       builder: (_, s) => const ChapterScreen()),
          GoRoute(path: '/broadcast',     builder: (_, s) => const BroadcastScreen()),
          GoRoute(path: '/invitations',   builder: (_, s) => const InvitationsScreen()),
          GoRoute(path: '/reports',       builder: (_, s) => const ReportsScreen()),
        ],
      ),
    ],
  );
}

/// Bottom navigation shell — shows nav bar for the 5 primary tabs
class _AppShell extends StatelessWidget {
  final GoRouterState state;
  final Widget child;
  const _AppShell({required this.state, required this.child});

  static const _tabs = [
    (path: '/dashboard',     icon: Icons.home_outlined,              activeIcon: Icons.home,                label: 'Home'),
    (path: '/connect',       icon: Icons.handshake_outlined,         activeIcon: Icons.handshake,           label: 'Connects'),
    (path: '/mitb',          icon: Icons.currency_rupee_outlined,    activeIcon: Icons.currency_rupee,      label: 'MITB'),
    (path: '/notifications', icon: Icons.notifications_outlined,     activeIcon: Icons.notifications,       label: 'Alerts'),
    (path: '/profile',       icon: Icons.person_outline,             activeIcon: Icons.person,              label: 'Profile'),
  ];

  int _selectedIndex(String path) {
    for (int i = 0; i < _tabs.length; i++) {
      if (path == _tabs[i].path) return i;
    }
    return 0; // Default to Home for sub-screens
  }

  @override
  Widget build(BuildContext context) {
    final currentPath = state.uri.path;
    final idx = _selectedIndex(currentPath);

    return Scaffold(
      body: child,
      bottomNavigationBar: NavigationBar(
        selectedIndex: idx,
        onDestinationSelected: (i) => context.go(_tabs[i].path),
        destinations: _tabs.map((t) => NavigationDestination(
          icon:         Icon(t.icon),
          selectedIcon: Icon(t.activeIcon),
          label:        t.label,
        )).toList(),
      ),
    );
  }
}
