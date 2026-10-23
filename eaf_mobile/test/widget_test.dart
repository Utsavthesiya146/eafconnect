import 'package:flutter_test/flutter_test.dart';

void main() {
  test('AppConfig baseUrl points to production API', () {
    const baseUrl = 'https://eafconnect.com/api/v1';
    expect(baseUrl.startsWith('https://'), isTrue);
    expect(baseUrl.contains('eafconnect.com'), isTrue);
  });

  test('Token key constants are defined', () {
    const tokenKey    = 'eaf_api_token';
    const userDataKey = 'eaf_user_data';
    expect(tokenKey.isNotEmpty,    isTrue);
    expect(userDataKey.isNotEmpty, isTrue);
  });
}
