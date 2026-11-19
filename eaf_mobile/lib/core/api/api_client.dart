import 'package:dio/dio.dart';
import '../config/app_config.dart';
import '../errors/exceptions.dart';
import 'demo_mock_data.dart';

/// Central HTTP Client for EAF Connect Mobile App.
/// • Real-time live synchronization with https://eafconnect.com
/// • Auto-injects Bearer token
/// • Maps HTTP errors to typed exceptions
/// • Supports multipart uploads
/// • Reviewer mode for App Store / Google Play testers using designated reviewer logins
class ApiClient {
  late final Dio _dio;
  String? _cachedToken;
  bool _isDemoMode = false;

  bool get isDemoMode => _isDemoMode;

  ApiClient() {
    _dio = Dio(BaseOptions(
      baseUrl:        AppConfig.baseUrl,
      connectTimeout: const Duration(seconds: AppConfig.connectTimeoutSeconds),
      receiveTimeout: const Duration(seconds: AppConfig.receiveTimeoutSeconds),
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json',
      },
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) {
        if (_cachedToken != null && _cachedToken!.isNotEmpty) {
          options.headers['Authorization'] = 'Bearer $_cachedToken';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        return handler.next(error);
      },
    ));
  }

  void setCachedToken(String? token) {
    _cachedToken = token;
    if (token != null && (token.startsWith('demo_') || token.contains('mock'))) {
      _isDemoMode = true;
    } else if (token != null) {
      _isDemoMode = false;
    }
  }

  void setDemoMode(bool isDemo) {
    _isDemoMode = isDemo;
  }

  // ── Internal request helper ───────────────────────────────────

  Future<Map<String, dynamic>> _request(Future<Response> Function() call) async {
    try {
      final resp = await call();
      return resp.data as Map<String, dynamic>;
    } on DioException catch (e) {
      final statusCode = e.response?.statusCode;
      final msg = (e.response?.data is Map)
          ? (e.response!.data['message'] as String? ?? e.message ?? 'Unknown error')
          : (e.message ?? 'Unknown error');

      switch (e.type) {
        case DioExceptionType.connectionTimeout:
        case DioExceptionType.receiveTimeout:
        case DioExceptionType.sendTimeout:
          throw const TimeoutException();
        case DioExceptionType.connectionError:
          throw const NetworkException();
        default:
          break;
      }

      switch (statusCode) {
        case 401: throw UnauthorizedException(message: msg);
        case 403: throw ForbiddenException(message: msg);
        case 404: throw NotFoundException(message: msg);
        case 422: throw ValidationException(message: msg, errors: e.response?.data is Map ? e.response!.data['errors'] : null);
        case 500: throw ServerException(message: msg);
        default:  throw ApiException(message: msg, statusCode: statusCode);
      }
    }
  }

  // ── Auth ─────────────────────────────────────────────────────

  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
    String deviceInfo = '',
  }) async {
    final em = email.trim().toLowerCase();
    // Immediate trigger for designated offline reviewer accounts
    if (em == 'demo@eafconnect.com' ||
        em == 'reviewer@eafconnect.com' ||
        em.startsWith('demo_reviewer')) {
      _isDemoMode = true;
      _cachedToken = 'demo_reviewer_session_token_eaf_998877';
      return DemoMockData.loginResponse();
    }

    // Try standard live API login
    try {
      final resp = await _request(() => _dio.post('/login', data: FormData.fromMap({
        'email': email, 'password': password, 'device_info': deviceInfo,
      })));
      _isDemoMode = false;
      return resp;
    } on ApiException catch (e) {
      return {'status': 'error', 'message': e.message};
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
    }
  }

  Future<void> logout() async {
    if (!_isDemoMode && _cachedToken != null) {
      try { await _dio.post('/logout'); } catch (_) {}
    }
    _cachedToken = null;
    _isDemoMode = false;
  }

  // ── Dashboard ────────────────────────────────────────────────

  Future<Map<String, dynamic>> getDashboard({String filter = '180'}) async {
    if (_isDemoMode) return DemoMockData.dashboardResponse();
    return await _request(() => _dio.get('/dashboard', queryParameters: {'filter': filter}));
  }

  // ── Profile ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getProfile() async {
    if (_isDemoMode) return DemoMockData.profileResponse();
    return await _request(() => _dio.get('/profile'));
  }

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Personal info saved!');
    return await _request(() => _dio.post('/profile/update', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateSkills(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Professional info saved!');
    return await _request(() => _dio.post('/profile/skills', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateBusiness(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Business info saved!');
    return await _request(() => _dio.post('/profile/business', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateSocial(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Social info saved!');
    return await _request(() => _dio.post('/profile/social', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> changePassword(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Password changed!');
    return await _request(() => _dio.post('/profile/password', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> uploadProfilePhoto(String filePath) async {
    if (_isDemoMode) return DemoMockData.successResponse('Profile photo updated!');
    final formData = FormData.fromMap({
      'profile_photo': await MultipartFile.fromFile(filePath, filename: 'photo.jpg'),
    });
    return await _request(() => _dio.post('/profile/photo', data: formData));
  }

  Future<Map<String, dynamic>> getMemberDetails(String userCode) async {
    if (_isDemoMode) return DemoMockData.profileResponse();
    return await _request(() => _dio.get('/profile/details/$userCode'));
  }

  // ── Connect (Referrals) ──────────────────────────────────────

  Future<Map<String, dynamic>> getConnects() async {
    if (_isDemoMode) return DemoMockData.connectsResponse();
    return await _request(() => _dio.get('/connect'));
  }

  Future<Map<String, dynamic>> addConnect(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Referral slip submitted!');
    return await _request(() => _dio.post('/connect/add', data: FormData.fromMap(data)));
  }

  Future<List<dynamic>> getChapterMembers() async {
    if (_isDemoMode) return DemoMockData.chapterMembersList();
    final resp = await _request(() => _dio.get('/connect/members'));
    return resp['data'] as List<dynamic>? ?? [];
  }

  // ── MITB ─────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMitb() async {
    if (_isDemoMode) return DemoMockData.mitbResponse();
    return await _request(() => _dio.get('/mitb'));
  }

  Future<Map<String, dynamic>> addMitb(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('MITB slip submitted!');
    return await _request(() => _dio.post('/mitb/add', data: FormData.fromMap(data)));
  }

  // ── F2F ──────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getF2f() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/f2f'));
  }

  Future<Map<String, dynamic>> addF2f(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('F2F scheduled!');
    return await _request(() => _dio.post('/f2f/add', data: FormData.fromMap(data)));
  }

  // ── Meetings ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMeetings() async {
    if (_isDemoMode) return DemoMockData.meetingsResponse();
    return await _request(() => _dio.get('/meetings'));
  }

  // ── Visitors ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getVisitors() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/visitors'));
  }

  Future<Map<String, dynamic>> addVisitor(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Visitor registered!');
    return await _request(() => _dio.post('/visitors/add', data: FormData.fromMap(data)));
  }

  // ── Invitations ──────────────────────────────────────────────

  Future<Map<String, dynamic>> getInvitations() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/invitations'));
  }

  Future<Map<String, dynamic>> addInvitation(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Invitation sent!');
    return await _request(() => _dio.post('/invitations/add', data: FormData.fromMap(data)));
  }

  // ── Notifications ────────────────────────────────────────────

  Future<Map<String, dynamic>> getNotifications() async {
    if (_isDemoMode) return DemoMockData.notificationsResponse();
    return await _request(() => _dio.get('/notifications'));
  }

  Future<void> markNotificationRead(String id) async {
    if (_isDemoMode) return;
    try {
      await _request(() => _dio.post('/notifications/read',
          data: FormData.fromMap({'notification_id': id})));
    } catch (_) {}
  }

  Future<void> markAllNotificationsRead() async {
    if (_isDemoMode) return;
    try {
      await _request(() => _dio.post('/notifications/read_all'));
    } catch (_) {}
  }

  // ── Chapter ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getChapter() async {
    if (_isDemoMode) return DemoMockData.chapterResponse();
    return await _request(() => _dio.get('/chapter'));
  }

  // ── Broadcasts ───────────────────────────────────────────────

  Future<Map<String, dynamic>> getBroadcasts() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/broadcasts'));
  }

  // ── Reports ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getWeeklyReport() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/reports/weekly'));
  }

  Future<Map<String, dynamic>> getParticipationReport() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    return await _request(() => _dio.get('/reports/participation'));
  }

  // ── Members search ───────────────────────────────────────────

  Future<Map<String, dynamic>> searchMembers(String query) async {
    if (_isDemoMode) return DemoMockData.chapterResponse();
    return await _request(() => _dio.get('/members/search', queryParameters: {'q': query}));
  }
}
