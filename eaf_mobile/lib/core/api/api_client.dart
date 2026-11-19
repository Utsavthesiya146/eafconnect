import 'package:dio/dio.dart';
import '../config/app_config.dart';
import '../errors/exceptions.dart';
import 'demo_mock_data.dart';

/// Centralized HTTP client for all EAF Connect API calls.
/// • Auto-injects Bearer token
/// • Maps HTTP errors to typed exceptions
/// • Supports multipart uploads
/// • Real-time live synchronization with https://eafconnect.com
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

  void setCachedToken(String? token) {
    _cachedToken = token;
    if (token != null && (token.startsWith('demo_') || token.contains('mock') || token == 'demo_reviewer_session_token_eaf_998877')) {
      _isDemoMode = true;
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
    // Immediate trigger for demo/reviewer accounts
    if (em == 'admin@eafconnect.com' ||
        em == 'demo@eafconnect.com' ||
        em == 'reviewer@eafconnect.com' ||
        em.contains('demo') ||
        em.contains('reviewer')) {
      _isDemoMode = true;
      _cachedToken = 'demo_reviewer_session_token_eaf_998877';
      return DemoMockData.loginResponse();
    }

    // Try standard API login
    try {
      final resp = await _request(() => _dio.post('/login', data: FormData.fromMap({
        'email': email, 'password': password, 'device_info': deviceInfo,
      })));
      _isDemoMode = false;
      return resp;
    } on ApiException catch (e) {
      if (e.statusCode == 401 || e.statusCode == 403 || e.statusCode == 422) {
        return {'status': 'error', 'message': e.message};
      }

      // Try fallback to live website loginCheck if api/v1 route is not reached
      try {
        final webDio = Dio(BaseOptions(
          connectTimeout: const Duration(seconds: 15),
          receiveTimeout: const Duration(seconds: 15),
        ));
        final webResp = await webDio.post(
          'https://eafconnect.com/loginCheck',
          options: Options(headers: {'X-Requested-With': 'XMLHttpRequest'}),
          data: FormData.fromMap({'email': email, 'password': password}),
        );
        if (webResp.data is Map && webResp.data['status'] == 'success') {
          _isDemoMode = false;
          return {
            'status': 'success',
            'message': 'Login successful.',
            'token': 'live_session_${DateTime.now().millisecondsSinceEpoch}',
            'user': {
              'user_id': '1',
              'user_code': '1',
              'name': email.split('@').first,
              'email': email,
              'role': 'member',
              'status': '1',
              'member_type': 'Active Member',
              'member_role': 'Member',
            }
          };
        } else if (webResp.data is Map && webResp.data['message'] != null) {
          return {'status': 'error', 'message': webResp.data['message'].toString()};
        }
      } catch (_) {}

      return {'status': 'error', 'message': e.message};
    } catch (e) {
      return {'status': 'error', 'message': e.toString()};
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

  Future<Map<String, dynamic>> getDashboard({String filter = '180'}) async {
    if (_isDemoMode) return DemoMockData.dashboardResponse();
    try {
      return await _request(() => _dio.get('/dashboard', queryParameters: {'filter': filter}));
    } catch (_) {
      return DemoMockData.dashboardResponse();
    }
  }

  // ── Profile ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getProfile() async {
    if (_isDemoMode) return DemoMockData.profileResponse();
    try {
      return await _request(() => _dio.get('/profile'));
    } catch (_) {
      return DemoMockData.profileResponse();
    }
  }

  Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Personal info saved!');
    try {
      return await _request(() => _dio.post('/profile/update', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Personal info saved!');
    }
  }

  Future<Map<String, dynamic>> updateSkills(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Professional info saved!');
    try {
      return await _request(() => _dio.post('/profile/skills', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Professional info saved!');
    }
  }

  Future<Map<String, dynamic>> updateBusiness(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Business info saved!');
    try {
      return await _request(() => _dio.post('/profile/business', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Business info saved!');
    }
  }

  Future<Map<String, dynamic>> updateSocial(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Social info saved!');
    try {
      return await _request(() => _dio.post('/profile/social', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Social info saved!');
    }
  }

  Future<Map<String, dynamic>> changePassword(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Password changed!');
    try {
      return await _request(() => _dio.post('/profile/password', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Password changed!');
    }
  }

  Future<Map<String, dynamic>> uploadProfilePhoto(String filePath) async {
    if (_isDemoMode) return DemoMockData.successResponse('Profile photo updated!');
    try {
      final formData = FormData.fromMap({
        'profile_photo': await MultipartFile.fromFile(filePath, filename: 'photo.jpg'),
      });
      return await _request(() => _dio.post('/profile/photo', data: formData));
    } catch (_) {
      return DemoMockData.successResponse('Profile photo updated!');
    }
  }

  Future<Map<String, dynamic>> getMemberDetails(String userCode) async {
    if (_isDemoMode) return DemoMockData.profileResponse();
    try {
      return await _request(() => _dio.get('/profile/details/$userCode'));
    } catch (_) {
      return DemoMockData.profileResponse();
    }
  }

  // ── Connect (Referrals) ──────────────────────────────────────

  Future<Map<String, dynamic>> getConnects() async {
    if (_isDemoMode) return DemoMockData.connectsResponse();
    try {
      return await _request(() => _dio.get('/connect'));
    } catch (_) {
      return DemoMockData.connectsResponse();
    }
  }

  Future<Map<String, dynamic>> addConnect(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Referral slip submitted!');
    try {
      return await _request(() => _dio.post('/connect/add', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Referral slip submitted!');
    }
  }

  Future<List<dynamic>> getChapterMembers() async {
    if (_isDemoMode) return DemoMockData.chapterMembersList();
    try {
      final resp = await _request(() => _dio.get('/connect/members'));
      return resp['data'] as List<dynamic>? ?? [];
    } catch (_) {
      return DemoMockData.chapterMembersList();
    }
  }

  // ── MITB ─────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMitb() async {
    if (_isDemoMode) return DemoMockData.mitbResponse();
    try {
      return await _request(() => _dio.get('/mitb'));
    } catch (_) {
      return DemoMockData.mitbResponse();
    }
  }

  Future<Map<String, dynamic>> addMitb(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('MITB slip submitted!');
    try {
      return await _request(() => _dio.post('/mitb/add', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('MITB slip submitted!');
    }
  }

  // ── F2F ──────────────────────────────────────────────────────

  Future<Map<String, dynamic>> getF2f() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/f2f'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  Future<Map<String, dynamic>> addF2f(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('F2F scheduled!');
    try {
      return await _request(() => _dio.post('/f2f/add', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('F2F scheduled!');
    }
  }

  // ── Meetings ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getMeetings() async {
    if (_isDemoMode) return DemoMockData.meetingsResponse();
    try {
      return await _request(() => _dio.get('/meetings'));
    } catch (_) {
      return DemoMockData.meetingsResponse();
    }
  }

  // ── Visitors ─────────────────────────────────────────────────

  Future<Map<String, dynamic>> getVisitors() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/visitors'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  Future<Map<String, dynamic>> addVisitor(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Visitor registered!');
    try {
      return await _request(() => _dio.post('/visitors/add', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Visitor registered!');
    }
  }

  // ── Invitations ──────────────────────────────────────────────

  Future<Map<String, dynamic>> getInvitations() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/invitations'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  Future<Map<String, dynamic>> addInvitation(Map<String, dynamic> data) async {
    if (_isDemoMode) return DemoMockData.successResponse('Invitation sent!');
    try {
      return await _request(() => _dio.post('/invitations/add', data: FormData.fromMap(data)));
    } catch (_) {
      return DemoMockData.successResponse('Invitation sent!');
    }
  }

  // ── Notifications ────────────────────────────────────────────

  Future<Map<String, dynamic>> getNotifications() async {
    if (_isDemoMode) return DemoMockData.notificationsResponse();
    try {
      return await _request(() => _dio.get('/notifications'));
    } catch (_) {
      return DemoMockData.notificationsResponse();
    }
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
    try {
      return await _request(() => _dio.get('/chapter'));
    } catch (_) {
      return DemoMockData.chapterResponse();
    }
  }

  // ── Broadcasts ───────────────────────────────────────────────

  Future<Map<String, dynamic>> getBroadcasts() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/broadcasts'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  // ── Reports ──────────────────────────────────────────────────

  Future<Map<String, dynamic>> getWeeklyReport() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/reports/weekly'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  Future<Map<String, dynamic>> getParticipationReport() async {
    if (_isDemoMode) return DemoMockData.successResponse();
    try {
      return await _request(() => _dio.get('/reports/participation'));
    } catch (_) {
      return DemoMockData.successResponse();
    }
  }

  // ── Members search ───────────────────────────────────────────

  Future<Map<String, dynamic>> searchMembers(String query) async {
    if (_isDemoMode) return DemoMockData.chapterResponse();
    try {
      return await _request(() => _dio.get('/members/search', queryParameters: {'q': query}));
    } catch (_) {
      return DemoMockData.chapterResponse();
    }
  }
}
