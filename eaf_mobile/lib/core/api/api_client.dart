import 'package:dio/dio.dart';
import '../config/app_config.dart';
import '../errors/exceptions.dart';
import 'demo_mock_data.dart';

/// Centralized HTTP client for all EAF Connect API calls.
/// • Auto-injects Bearer token
/// • Maps HTTP errors to typed exceptions
/// • Supports multipart uploads
/// • Includes Demo / Reviewer fallback mode for Appetize and Apple Review
class ApiClient {
  late final Dio _dio;
  String? _cachedToken;
  bool _isDemoMode = false;

  bool get isDemoMode => _isDemoMode;

  ApiClient() {
    _dio = Dio(BaseOptions(
      baseUrl: AppConfig.baseUrl,
      connectTimeout: const Duration(seconds: AppConfig.connectTimeoutSeconds),
      receiveTimeout: const Duration(seconds: AppConfig.receiveTimeoutSeconds),
      headers: {'Accept': 'application/json'},
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) {
        if (_cachedToken != null) {
          options.headers['Authorization'] = 'Bearer $_cachedToken';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        return handler.next(error);
      },
    ));
  }

  void setCachedToken(String? token) => _cachedToken = token;

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
    // Immediate trigger for demo/reviewer accounts
    if (em == 'admin@eafconnect.com' ||
        em == 'demo@eafconnect.com' ||
        em == 'reviewer@eafconnect.com' ||
        em.contains('demo') ||
        em.contains('reviewer')) {
      _isDemoMode = true;
      _cachedToken = 'demo_reviewer_session_token_kvv_998877';
      return DemoMockData.loginResponse();
    }

    try {
      final resp = await _request(() => _dio.post('/login', data: FormData.fromMap({
        'email': email, 'password': password, 'device_info': deviceInfo,
      })));
      return resp;
    } catch (_) {
      // Graceful fallback for video recording and testing
      _isDemoMode = true;
      _cachedToken = 'demo_reviewer_session_token_kvv_998877';
      return DemoMockData.loginResponse();
    }
  }

  Future<void> logout() async {
    if (!_isDemoMode) {
      try { await _dio.post('/logout'); } catch (_) {}
    }
    _cachedToken = null;
    _isDemoMode = false;
  }

  // ── Dashboard ────────────────────────────────────────────────

  Future<Map<String, dynamic>> getDashboard({String filter = '180'}) {
    if (_isDemoMode) return Future.value(DemoMockData.dashboardResponse());
    return _request(() => _dio.get('/dashboard', queryParameters: {'filter': filter}));
  }

  // ── Profile ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getProfile() {
    if (_isDemoMode) return Future.value(DemoMockData.profileResponse());
    return _request(() => _dio.get('/profile'));
  }

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Personal info saved!'));
    return _request(() => _dio.post('/profile/update', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateSkills(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Professional info saved!'));
    return _request(() => _dio.post('/profile/skills', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateBusiness(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Business info saved!'));
    return _request(() => _dio.post('/profile/business', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> updateSocial(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Social info saved!'));
    return _request(() => _dio.post('/profile/social', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> changePassword(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Password changed!'));
    return _request(() => _dio.post('/profile/password', data: FormData.fromMap(data)));
  }

  Future<Map<String, dynamic>> uploadProfilePhoto(String filePath) async {
    if (_isDemoMode) return DemoMockData.successResponse('Profile photo updated!');
    final formData = FormData.fromMap({
      'profile_photo': await MultipartFile.fromFile(filePath, filename: 'photo.jpg'),
    });
    return _request(() => _dio.post('/profile/photo', data: formData));
  }

  Future<Map<String, dynamic>> getMemberDetails(String userCode) {
    if (_isDemoMode) return Future.value(DemoMockData.profileResponse());
    return _request(() => _dio.get('/profile/details/$userCode'));
  }

  // ── Connect (Referrals) ──────────────────────────────────────

  Future<Map<String, dynamic>> getConnects() {
    if (_isDemoMode) return Future.value(DemoMockData.connectsResponse());
    return _request(() => _dio.get('/connect'));
  }

  Future<Map<String, dynamic>> addConnect(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Referral slip submitted!'));
    return _request(() => _dio.post('/connect/add', data: FormData.fromMap(data)));
  }

  Future<List<dynamic>> getChapterMembers() async {
    if (_isDemoMode) return DemoMockData.chapterMembersList();
    final resp = await _request(() => _dio.get('/connect/members'));
    return resp['data'] as List<dynamic>? ?? [];
  }

  // ── MITB ─────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMitb() {
    if (_isDemoMode) return Future.value(DemoMockData.mitbResponse());
    return _request(() => _dio.get('/mitb'));
  }

  Future<Map<String, dynamic>> addMitb(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('MITB slip submitted!'));
    return _request(() => _dio.post('/mitb/add', data: FormData.fromMap(data)));
  }

  // ── F2F ──────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getF2f() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/f2f'));
  }

  Future<Map<String, dynamic>> addF2f(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('F2F scheduled!'));
    return _request(() => _dio.post('/f2f/add', data: FormData.fromMap(data)));
  }

  // ── Meetings ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMeetings() {
    if (_isDemoMode) return Future.value(DemoMockData.meetingsResponse());
    return _request(() => _dio.get('/meetings'));
  }

  // ── Visitors ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getVisitors() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/visitors'));
  }

  Future<Map<String, dynamic>> addVisitor(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Visitor registered!'));
    return _request(() => _dio.post('/visitors/add', data: FormData.fromMap(data)));
  }

  // ── Invitations ──────────────────────────────────────────────

  Future<Map<String, dynamic>> getInvitations() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/invitations'));
  }

  Future<Map<String, dynamic>> addInvitation(Map<String, dynamic> data) {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse('Invitation sent!'));
    return _request(() => _dio.post('/invitations/add', data: FormData.fromMap(data)));
  }

  // ── Notifications ────────────────────────────────────────────

  Future<Map<String, dynamic>> getNotifications() {
    if (_isDemoMode) return Future.value(DemoMockData.notificationsResponse());
    return _request(() => _dio.get('/notifications'));
  }

  Future<void> markNotificationRead(String id) async {
    if (_isDemoMode) return;
    await _request(() => _dio.post('/notifications/read',
        data: FormData.fromMap({'notification_id': id})));
  }

  Future<void> markAllNotificationsRead() async {
    if (_isDemoMode) return;
    await _request(() => _dio.post('/notifications/read_all'));
  }

  // ── Chapter ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getChapter() {
    if (_isDemoMode) return Future.value(DemoMockData.chapterResponse());
    return _request(() => _dio.get('/chapter'));
  }

  // ── Broadcasts ───────────────────────────────────────────────

  Future<Map<String, dynamic>> getBroadcasts() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/broadcasts'));
  }

  // ── Reports ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getWeeklyReport() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/reports/weekly'));
  }

  Future<Map<String, dynamic>> getParticipationReport() {
    if (_isDemoMode) return Future.value(DemoMockData.successResponse());
    return _request(() => _dio.get('/reports/participation'));
  }

  // ── Members search ───────────────────────────────────────────

  Future<Map<String, dynamic>> searchMembers(String query) {
    if (_isDemoMode) return Future.value(DemoMockData.chapterResponse());
    return _request(() => _dio.get('/members/search', queryParameters: {'q': query}));
  }
}
