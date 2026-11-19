import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'core/auth/auth_provider.dart';
import 'screens/portal/portal_screen.dart';
import 'screens/splash/splash_screen.dart';

final GlobalKey<NavigatorState> rootNavigatorKey =
    GlobalKey<NavigatorState>(debugLabel: 'root');

GoRouter createRouter(AuthProvider authProvider) {
  return GoRouter(
    navigatorKey: rootNavigatorKey,
    initialLocation: '/splash',
    routes: [
      GoRoute(
        path: '/splash',
        parentNavigatorKey: rootNavigatorKey,
        builder: (_, s) => const SplashScreen(),
      ),
      GoRoute(
        path: '/portal',
        parentNavigatorKey: rootNavigatorKey,
        builder: (_, s) => const PortalScreen(),
      ),
    ],
  );
}
