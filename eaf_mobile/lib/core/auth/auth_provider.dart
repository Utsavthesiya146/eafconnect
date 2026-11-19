import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../api/api_client.dart';
import '../config/app_config.dart';
import '../../models/user_model.dart';

enum AuthStatus { unknown, authenticated, unauthenticated }

/// Central authentication state provider.
/// Handles login, logout, token persistence, and auto-login.
class AuthProvider extends ChangeNotifier {
  final ApiClient apiClient;
  final _storage = const FlutterSecureStorage(
    aOptions: AndroidOptions(encryptedSharedPreferences: false),
    iOptions: IOSOptions(accessibility: KeychainAccessibility.first_unlock),
  );

  AuthStatus _status  = AuthStatus.unknown;
  UserModel? _user;
  bool _isLoading     = true;
  String? _error;

  AuthProvider({required this.apiClient});

  // ── Getters ──────────────────────────────────────────────────

  AuthStatus get status    => _status;
  UserModel? get user      => _user;
  bool get isLoading       => _isLoading;
  bool get isLoggedIn      => _status == AuthStatus.authenticated;
  bool get isAdmin         => _user?.role == 'admin';
  bool get isMember        => _user?.role == 'member';
  String? get error        => _error;

  // ── Auto login on app start ──────────────────────────────────

  Future<void> tryAutoLogin() async {
    _isLoading = true;
    notifyListeners();

    try {
      final prefs = await SharedPreferences.getInstance();
      String? token    = prefs.getString(AppConfig.tokenKey);
      String? userData = prefs.getString(AppConfig.userDataKey);

      // Fallback: check secure storage if SharedPreferences doesn't have it
      if (token == null || userData == null || token.isEmpty) {
        try {
          token    = await _storage.read(key: AppConfig.tokenKey);
          userData = await _storage.read(key: AppConfig.userDataKey);
          if (token != null && userData != null && token.isNotEmpty) {
            // Sync to SharedPreferences for permanent persistence
            await prefs.setString(AppConfig.tokenKey, token);
            await prefs.setString(AppConfig.userDataKey, userData);
          }
        } catch (_) {}
      }

      if (token != null && userData != null && token.isNotEmpty) {
        // Restore user from cache
        final json = jsonDecode(userData) as Map<String, dynamic>;
        _user   = UserModel.fromJson(json);
        _status = AuthStatus.authenticated;
        apiClient.setCachedToken(token);
      } else {
        _status = AuthStatus.unauthenticated;
      }
    } catch (_) {
      _status = AuthStatus.unauthenticated;
    }

    _isLoading = false;
    notifyListeners();
  }

  // ── Login ────────────────────────────────────────────────────

  Future<bool> login({
    required String email,
    required String password,
    String deviceInfo = '',
  }) async {
    _error = null;
    notifyListeners();

    try {
      final resp = await apiClient.login(
        email: email,
        password: password,
        deviceInfo: deviceInfo,
      );

      if (resp['status'] == 'success') {
        final token    = resp['token'] as String;
        final userJson = resp['user']  as Map<String, dynamic>;

        _user   = UserModel.fromJson(userJson);
        _status = AuthStatus.authenticated;

        // Persist permanently in both SharedPreferences and SecureStorage
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(AppConfig.tokenKey,    token);
        await prefs.setString(AppConfig.userDataKey, jsonEncode(userJson));

        try {
          await _storage.write(key: AppConfig.tokenKey,    value: token);
          await _storage.write(key: AppConfig.userDataKey, value: jsonEncode(userJson));
        } catch (_) {}

        apiClient.setCachedToken(token);

        notifyListeners();
        return true;
      } else {
        _error = resp['message'] as String? ?? 'Login failed.';
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = e.toString().replaceAll('Exception: ', '');
      notifyListeners();
      return false;
    }
  }

  // ── Logout ───────────────────────────────────────────────────

  Future<void> logout() async {
    try {
      await apiClient.logout();
    } catch (_) {}

    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(AppConfig.tokenKey);
    await prefs.remove(AppConfig.userDataKey);

    try {
      await _storage.delete(key: AppConfig.tokenKey);
      await _storage.delete(key: AppConfig.userDataKey);
    } catch (_) {}

    apiClient.setCachedToken(null);

    _user   = null;
    _status = AuthStatus.unauthenticated;
    _error  = null;
    notifyListeners();
  }

  // ── Update cached user data (after profile edit) ─────────────

  Future<void> refreshUser(Map<String, dynamic> userJson) async {
    _user = UserModel.fromJson(userJson);
    final jsonStr = jsonEncode(userJson);

    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(AppConfig.userDataKey, jsonStr);

    try {
      await _storage.write(key: AppConfig.userDataKey, value: jsonStr);
    } catch (_) {}

    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
