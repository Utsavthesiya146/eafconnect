import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
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
    aOptions: AndroidOptions(encryptedSharedPreferences: true),
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
      final token    = await _storage.read(key: AppConfig.tokenKey);
      final userData = await _storage.read(key: AppConfig.userDataKey);

      if (token != null && userData != null) {
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

        // Persist securely
        await _storage.write(key: AppConfig.tokenKey,    value: token);
        await _storage.write(key: AppConfig.userDataKey, value: jsonEncode(userJson));
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

    await _storage.delete(key: AppConfig.tokenKey);
    await _storage.delete(key: AppConfig.userDataKey);
    apiClient.setCachedToken(null);

    _user   = null;
    _status = AuthStatus.unauthenticated;
    _error  = null;
    notifyListeners();
  }

  // ── Update cached user data (after profile edit) ─────────────

  Future<void> refreshUser(Map<String, dynamic> userJson) async {
    _user = UserModel.fromJson(userJson);
    await _storage.write(key: AppConfig.userDataKey, value: jsonEncode(userJson));
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
