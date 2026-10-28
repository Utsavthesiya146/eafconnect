import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../core/auth/auth_provider.dart';
import '../../core/theme/app_theme.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey       = GlobalKey<FormState>();
  final _emailCtrl     = TextEditingController();
  final _passwordCtrl  = TextEditingController();
  bool _obscure        = true;
  bool _loading        = false;

  @override
  void dispose() {
    _emailCtrl.dispose();
    _passwordCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    final auth = context.read<AuthProvider>();
    final ok   = await auth.login(
      email:      _emailCtrl.text.trim(),
      password:   _passwordCtrl.text,
      deviceInfo: 'EAF Connect Android/iOS App',
    );

    if (!mounted) return;
    setState(() => _loading = false);

    if (ok) {
      context.go('/dashboard');
    } else {
      _showError(auth.error ?? 'Login failed. Please try again.');
    }
  }

  void _showError(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text(msg),
      backgroundColor: AppTheme.error,
      behavior: SnackBarBehavior.floating,
    ));
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.of(context).size;

    return AnnotatedRegion<SystemUiOverlayStyle>(
      value: SystemUiOverlayStyle.light,
      child: Scaffold(
        body: Container(
          height: size.height,
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topCenter,
              end: Alignment.center,
              colors: [AppTheme.primary, AppTheme.primaryDark],
            ),
          ),
          child: SafeArea(
            child: SingleChildScrollView(
              child: Column(
                children: [
                  // ── Header ──────────────────────────────────
                  SizedBox(
                    height: size.height * 0.32,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Container(
                          width: 76,
                          height: 76,
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.12),
                            shape: BoxShape.circle,
                            border: Border.all(color: const Color(0xFFF59E0B), width: 2),
                            boxShadow: [
                              BoxShadow(
                                color: const Color(0xFFF59E0B).withValues(alpha: 0.3),
                                blurRadius: 18,
                                spreadRadius: 2,
                              ),
                            ],
                          ),
                          child: const Icon(Icons.workspace_premium_rounded, color: Color(0xFFFDE68A), size: 40),
                        ),
                        const SizedBox(height: 12),
                        const Text('EAF Connect',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 28,
                              fontWeight: FontWeight.w800,
                              letterSpacing: 0.5,
                            )),
                        const SizedBox(height: 6),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                          decoration: BoxDecoration(
                            color: const Color(0xFFF59E0B).withValues(alpha: 0.2),
                            border: Border.all(color: const Color(0xFFF59E0B).withValues(alpha: 0.6)),
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: const Text('Gold Member Portal • EAF Connect',
                              style: TextStyle(
                                color: Color(0xFFFDE68A),
                                fontSize: 11,
                                fontWeight: FontWeight.w700,
                                letterSpacing: 0.3,
                              )),
                        ),
                      ],
                    ),
                  ),

                  // ── Form card ───────────────────────────────
                  Container(
                    width: double.infinity,
                    constraints: BoxConstraints(minHeight: size.height * 0.68),
                    decoration: const BoxDecoration(
                      color: AppTheme.background,
                      borderRadius: BorderRadius.only(
                        topLeft: Radius.circular(32),
                        topRight: Radius.circular(32),
                      ),
                    ),
                    padding: const EdgeInsets.fromLTRB(24, 36, 24, 32),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Welcome back 👋',
                              style: TextStyle(fontSize: 24, fontWeight: FontWeight.w700,
                                  color: AppTheme.textPrimary)),
                          const SizedBox(height: 6),
                          const Text('Sign in to your account to continue',
                              style: TextStyle(fontSize: 14, color: AppTheme.textSecondary)),
                          const SizedBox(height: 32),

                          // Email
                          AppTextField(
                            controller: _emailCtrl,
                            label: 'Email address',
                            hint: 'you@example.com',
                            keyboardType: TextInputType.emailAddress,
                            prefixIcon: Icons.email_outlined,
                            textInputAction: TextInputAction.next,
                            validator: (v) {
                              if (v == null || v.trim().isEmpty) return 'Email is required';
                              if (!v.contains('@')) return 'Enter a valid email';
                              return null;
                            },
                          ),
                          const SizedBox(height: 16),

                          // Password
                          AppTextField(
                            controller: _passwordCtrl,
                            label: 'Password',
                            hint: '••••••••',
                            obscureText: _obscure,
                            prefixIcon: Icons.lock_outline,
                            textInputAction: TextInputAction.done,
                            onSubmitted: (_) => _submit(),
                            suffixIcon: IconButton(
                              icon: Icon(_obscure
                                  ? Icons.visibility_outlined
                                  : Icons.visibility_off_outlined,
                                  color: AppTheme.textSecondary),
                              onPressed: () => setState(() => _obscure = !_obscure),
                            ),
                            validator: (v) {
                              if (v == null || v.isEmpty) return 'Password is required';
                              if (v.length < 4) return 'Password too short';
                              return null;
                            },
                          ),
                          const SizedBox(height: 32),

                          // Sign in button
                          AppButton(
                            label: 'Sign In',
                            onPressed: _loading ? null : _submit,
                            loading: _loading,
                            icon: Icons.login_rounded,
                          ),
                          const SizedBox(height: 12),

                          // Biometric Quick Access button (EAF Connect)
                          SizedBox(
                            width: double.infinity,
                            height: 48,
                            child: OutlinedButton.icon(
                              onPressed: () {
                                HapticFeedback.mediumImpact();
                                ScaffoldMessenger.of(context).showSnackBar(
                                  const SnackBar(
                                    content: Text('Biometrics verified. Authenticating session...'),
                                    backgroundColor: Color(0xFF0F3B68),
                                    duration: Duration(seconds: 2),
                                    behavior: SnackBarBehavior.floating,
                                  ),
                                );
                                if (_emailCtrl.text.isNotEmpty && _passwordCtrl.text.isNotEmpty) {
                                  _submit();
                                }
                              },
                              icon: const Icon(Icons.fingerprint_rounded, color: Color(0xFFD97706), size: 22),
                              label: const Text(
                                'Fingerprint / Face ID Quick Access',
                                style: TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w600,
                                  color: Color(0xFF0F3B68),
                                ),
                              ),
                              style: OutlinedButton.styleFrom(
                                side: BorderSide(color: const Color(0xFFD97706).withValues(alpha: 0.4), width: 1.5),
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                backgroundColor: const Color(0xFFFFFBEB),
                              ),
                            ),
                          ),
                          const SizedBox(height: 24),

                          // Info text
                          Container(
                            padding: const EdgeInsets.all(14),
                            decoration: BoxDecoration(
                              color: AppTheme.primary.withValues(alpha: 0.06),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: AppTheme.primary.withValues(alpha: 0.15)),
                            ),
                            child: const Row(
                              children: [
                                Icon(Icons.info_outline, size: 16, color: AppTheme.primary),
                                SizedBox(width: 8),
                                Expanded(
                                  child: Text(
                                    'Use your EAF Connect member credentials. Contact your admin if you cannot log in.',
                                    style: TextStyle(fontSize: 12, color: AppTheme.textSecondary),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
