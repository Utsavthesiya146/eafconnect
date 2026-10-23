import 'package:dio/dio.dart';
import '../config/app_config.dart';
import '../errors/exceptions.dart';

/// Centralized HTTP client for all EAF Connect API calls.
/// • Auto-injects Bearer token
/// • Maps HTTP errors to typed exceptions
/// • Supports multipart uploads
class ApiClient {
  late final Dio _dio;
  String? _cachedToken;

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
  }) => _request(() => _dio.post('/login', data: FormData.fromMap({
    'email': email, 'password': password, 'device_info': deviceInfo,
  })));

  Future<void> logout() async {
    try { await _dio.post('/logout'); } catch (_) {}
    _cachedToken = null;
  }

  // ── Dashboard ────────────────────────────────────────────────

  Future<Map<String, dynamic>> getDashboard({String filter = '180'}) =>
      _request(() => _dio.get('/dashboard', queryParameters: {'filter': filter}));

  // ── Profile ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getProfile() =>
      _request(() => _dio.get('/profile'));

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) =>
      _request(() => _dio.post('/profile/update', data: FormData.fromMap(data)));

  Future<Map<String, dynamic>> updateSkills(Map<String, dynamic> data) =>
      _request(() => _dio.post('/profile/skills', data: FormData.fromMap(data)));

  Future<Map<String, dynamic>> updateBusiness(Map<String, dynamic> data) =>
      _request(() => _dio.post('/profile/business', data: FormData.fromMap(data)));

  Future<Map<String, dynamic>> updateSocial(Map<String, dynamic> data) =>
      _request(() => _dio.post('/profile/social', data: FormData.fromMap(data)));

  Future<Map<String, dynamic>> changePassword(Map<String, dynamic> data) =>
      _request(() => _dio.post('/profile/password', data: FormData.fromMap(data)));

  Future<Map<String, dynamic>> uploadProfilePhoto(String filePath) async {
    final formData = FormData.fromMap({
      'profile_photo': await MultipartFile.fromFile(filePath, filename: 'photo.jpg'),
    });
    return _request(() => _dio.post('/profile/photo', data: formData));
  }

  Future<Map<String, dynamic>> getMemberDetails(String userCode) =>
      _request(() => _dio.get('/profile/details/$userCode'));

  // ── Connect (Referrals) ──────────────────────────────────────

  Future<Map<String, dynamic>> getConnects() =>
      _request(() => _dio.get('/connect'));

  Future<Map<String, dynamic>> addConnect(Map<String, dynamic> data) =>
      _request(() => _dio.post('/connect/add', data: FormData.fromMap(data)));

  Future<List<dynamic>> getChapterMembers() async {
    final resp = await _request(() => _dio.get('/connect/members'));
    return resp['data'] as List<dynamic>? ?? [];
  }

  // ── MITB ─────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMitb() =>
      _request(() => _dio.get('/mitb'));

  Future<Map<String, dynamic>> addMitb(Map<String, dynamic> data) =>
      _request(() => _dio.post('/mitb/add', data: FormData.fromMap(data)));

  // ── F2F ──────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getF2f() =>
      _request(() => _dio.get('/f2f'));

  Future<Map<String, dynamic>> addF2f(Map<String, dynamic> data) =>
      _request(() => _dio.post('/f2f/add', data: FormData.fromMap(data)));

  // ── Meetings ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMeetings() =>
      _request(() => _dio.get('/meetings'));

  // ── Visitors ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getVisitors() =>
      _request(() => _dio.get('/visitors'));

  Future<Map<String, dynamic>> addVisitor(Map<String, dynamic> data) =>
      _request(() => _dio.post('/visitors/add', data: FormData.fromMap(data)));

  // ── Invitations ──────────────────────────────────────────────

  Future<Map<String, dynamic>> getInvitations() =>
      _request(() => _dio.get('/invitations'));

  Future<Map<String, dynamic>> addInvitation(Map<String, dynamic> data) =>
      _request(() => _dio.post('/invitations/add', data: FormData.fromMap(data)));

  // ── Notifications ────────────────────────────────────────────

  Future<Map<String, dynamic>> getNotifications() =>
      _request(() => _dio.get('/notifications'));

  Future<void> markNotificationRead(String id) async {
    await _request(() => _dio.post('/notifications/read',
        data: FormData.fromMap({'notification_id': id})));
  }

  Future<void> markAllNotificationsRead() async {
    await _request(() => _dio.post('/notifications/read_all'));
  }

  // ── Chapter ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getChapter() =>
      _request(() => _dio.get('/chapter'));

  // ── Broadcasts ───────────────────────────────────────────────

  Future<Map<String, dynamic>> getBroadcasts() =>
      _request(() => _dio.get('/broadcasts'));

  // ── Reports ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getWeeklyReport() =>
      _request(() => _dio.get('/reports/weekly'));

  Future<Map<String, dynamic>> getParticipationReport() =>
      _request(() => _dio.get('/reports/participation'));

  // ── Members search ───────────────────────────────────────────

  Future<Map<String, dynamic>> searchMembers(String query) =>
      _request(() => _dio.get('/members/search', queryParameters: {'q': query}));
}
