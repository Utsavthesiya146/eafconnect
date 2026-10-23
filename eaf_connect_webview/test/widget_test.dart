import 'package:flutter_test/flutter_test.dart';
import 'package:eaf_connect_webview/main.dart';

void main() {
  testWidgets('App smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const EafConnectApp());
    expect(find.text('EAF Connect'), findsWidgets);
  });
}
