/// App-wide constants and configuration
class AppConfig {
  AppConfig._();

  // ── API ──────────────────────────────────────────────────────
  static const String baseUrl        = 'https://eafconnect.com/api/v1';
  static const String websiteBaseUrl = 'https://eafconnect.com';

  // Upload paths (relative to websiteBaseUrl)
  static const String profileUploadPath       = '/uploads/profile/';
  static const String businessLogoUploadPath  = '/uploads/business_logo/';
  static const String businessCardUploadPath  = '/uploads/business_cards/';
  static const String brochureUploadPath      = '/uploads/brochures/';
  static const String defaultAvatarPath       = '/assets/img/empty.png';

  // ── Timeouts ─────────────────────────────────────────────────
  static const int connectTimeoutSeconds = 15;
  static const int receiveTimeoutSeconds = 30;

  // ── Token ────────────────────────────────────────────────────
  static const String tokenKey    = 'eaf_api_token';
  static const String userDataKey = 'eaf_user_data';

  // ── Pagination ───────────────────────────────────────────────
  static const int defaultPageSize = 20;

  // ── Helpers ──────────────────────────────────────────────────
  static String profilePhotoUrl(String? photo) {
    if (photo == null || photo.isEmpty || photo == 'empty.png') {
      return '$websiteBaseUrl$defaultAvatarPath';
    }
    return '$websiteBaseUrl$profileUploadPath$photo';
  }

  static String businessLogoUrl(String? logo) {
    if (logo == null || logo.isEmpty) return '';
    return '$websiteBaseUrl$businessLogoUploadPath$logo';
  }
}
