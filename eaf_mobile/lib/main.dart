import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:provider/provider.dart';
import 'core/auth/auth_provider.dart';
import 'core/theme/app_theme.dart';
import 'core/api/api_client.dart';
import 'router.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Lock to portrait mode
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);

  // Set status bar style
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
    ),
  );

  final apiClient  = ApiClient();
  final authProvider = AuthProvider(apiClient: apiClient);

  // Restore session from secure storage
  await authProvider.tryAutoLogin();

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider.value(value: authProvider),
        Provider.value(value: apiClient),
      ],
      child: const EafConnectApp(),
    ),
  );
}

class EafConnectApp extends StatelessWidget {
  const EafConnectApp({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();

    return MaterialApp.router(
      title: 'EAF Connect',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.light(),
      routerConfig: createRouter(authProvider),
    );
  }
}
