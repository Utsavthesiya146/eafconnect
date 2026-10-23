import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import 'package:go_router/go_router.dart';
import '../../core/auth/auth_provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/user_model.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});
  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> with SingleTickerProviderStateMixin {
  late TabController _tabs;
  UserModel? _user;
  bool _loading = true;
  String? _error;

  // Personal info controllers
  final _nameCtrl       = TextEditingController();
  final _lastNameCtrl   = TextEditingController();
  final _emailCtrl      = TextEditingController();
  final _phoneCtrl      = TextEditingController();
  final _whatsappCtrl   = TextEditingController();
  final _addressCtrl    = TextEditingController();
  final _cityCtrl       = TextEditingController();
  final _stateCtrl      = TextEditingController();
  final _pincodeCtrl    = TextEditingController();
  String? _gender;

  // Skills controllers
  final _designationCtrl = TextEditingController();
  final _expCtrl         = TextEditingController();
  final _skillsCtrl      = TextEditingController();
  final _servicesCtrl    = TextEditingController();
  final _bioCtrl         = TextEditingController();

  // Business controllers
  final _bizNameCtrl = TextEditingController();
  final _industryCtrl = TextEditingController();
  final _descCtrl    = TextEditingController();
  final _gstCtrl     = TextEditingController();

  // Social controllers
  final _websiteCtrl  = TextEditingController();
  final _linkedinCtrl = TextEditingController();
  final _fbCtrl       = TextEditingController();
  final _igCtrl       = TextEditingController();

  // Password controllers
  final _currPwCtrl    = TextEditingController();
  final _newPwCtrl     = TextEditingController();
  final _confirmPwCtrl = TextEditingController();

  @override
  void initState() {
    super.initState();
    _tabs = TabController(length: 4, vsync: this);
    _loadProfile();
  }

  @override
  void dispose() {
    _tabs.dispose();
    for (final c in [_nameCtrl, _lastNameCtrl, _emailCtrl, _phoneCtrl, _whatsappCtrl,
      _addressCtrl, _cityCtrl, _stateCtrl, _pincodeCtrl, _designationCtrl, _expCtrl,
      _skillsCtrl, _servicesCtrl, _bioCtrl, _bizNameCtrl, _industryCtrl, _descCtrl,
      _gstCtrl, _websiteCtrl, _linkedinCtrl, _fbCtrl, _igCtrl,
      _currPwCtrl, _newPwCtrl, _confirmPwCtrl]) { c.dispose(); }
    super.dispose();
  }

  Future<void> _loadProfile() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getProfile();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        final u    = UserModel.fromJson(data['user'] as Map<String, dynamic>);
        setState(() { _user = u; });
        _populate(u);
      }
    } catch (e) {
      setState(() => _error = e.toString().replaceAll('Exception: ', ''));
    } finally {
      setState(() => _loading = false);
    }
  }

  void _populate(UserModel u) {
    _nameCtrl.text      = u.name;
    _lastNameCtrl.text  = u.lastName;
    _emailCtrl.text     = u.email;
    _phoneCtrl.text     = u.phone;
    _whatsappCtrl.text  = u.phone;
    _addressCtrl.text   = u.address ?? '';
    _cityCtrl.text      = u.city    ?? '';
    _stateCtrl.text     = u.state   ?? '';
    _pincodeCtrl.text   = u.pincode ?? '';
    _gender             = u.gender;
    _designationCtrl.text = u.designation ?? '';
    _expCtrl.text         = u.expYears    ?? '';
    _skillsCtrl.text      = u.skills      ?? '';
    _servicesCtrl.text    = u.skills      ?? '';
    _bioCtrl.text         = u.bio         ?? '';
    _bizNameCtrl.text  = u.businessName ?? '';
    _industryCtrl.text = u.industry     ?? '';
    _descCtrl.text     = u.description  ?? '';
    _gstCtrl.text      = u.gstNumber    ?? '';
    _websiteCtrl.text  = u.website      ?? '';
    _linkedinCtrl.text = u.linkedin     ?? '';
    _fbCtrl.text       = u.facebook     ?? '';
    _igCtrl.text       = u.instagram    ?? '';
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    if (_error != null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Profile')),
        body: Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
          Text(_error!, textAlign: TextAlign.center),
          const SizedBox(height: 16),
          AppButton(label: 'Retry', onPressed: _loadProfile, width: 120),
        ])),
      );
    }

    return Scaffold(
      appBar: AppBar(title: const Text('My Profile')),
      body: Column(
        children: [
          _buildHeader(),
          TabBar(
            controller: _tabs,
            labelColor: AppTheme.primary,
            unselectedLabelColor: AppTheme.textSecondary,
            indicatorColor: AppTheme.primary,
            indicatorWeight: 3,
            isScrollable: true,
            tabs: const [
              Tab(text: 'Personal'),
              Tab(text: 'Professional'),
              Tab(text: 'Business'),
              Tab(text: 'Account'),
            ],
          ),
          Expanded(
            child: TabBarView(
              controller: _tabs,
              children: [
                _buildPersonalTab(),
                _buildProfessionalTab(),
                _buildBusinessTab(),
                _buildAccountTab(),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader() {
    final photoUrl = _user?.photoUrl ?? '';
    return Container(
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          colors: [Color(0xFF0F3B68), Color(0xFF1E293B)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      padding: const EdgeInsets.fromLTRB(20, 16, 20, 24),
      child: Row(
        children: [
          GestureDetector(
            onTap: _pickPhoto,
            child: Stack(children: [
              Container(
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: const Color(0xFFF59E0B), width: 2.5),
                  boxShadow: [
                    BoxShadow(
                      color: const Color(0xFFF59E0B).withValues(alpha: 0.25),
                      blurRadius: 10,
                      spreadRadius: 1,
                    ),
                  ],
                ),
                child: CircleAvatar(
                  radius: 38,
                  backgroundColor: Colors.white24,
                  backgroundImage: photoUrl.isNotEmpty && !photoUrl.contains('empty.png')
                      ? NetworkImage(photoUrl) : null,
                  child: photoUrl.isEmpty || photoUrl.contains('empty.png')
                      ? Text(_user?.name.isNotEmpty == true ? _user!.name[0].toUpperCase() : '?',
                          style: const TextStyle(fontSize: 28, color: Colors.white, fontWeight: FontWeight.w700))
                      : null,
                ),
              ),
              Positioned(bottom: 0, right: 0, child: Container(
                padding: const EdgeInsets.all(5),
                decoration: const BoxDecoration(color: Color(0xFFD97706), shape: BoxShape.circle),
                child: const Icon(Icons.camera_alt_rounded, size: 14, color: Colors.white),
              )),
            ]),
          ),
          const SizedBox(width: 16),
          Expanded(child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Flexible(
                    child: Text(_user?.fullName ?? '',
                        maxLines: 1, overflow: TextOverflow.ellipsis,
                        style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w800)),
                  ),
                  const SizedBox(width: 6),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 2),
                    decoration: BoxDecoration(
                      color: const Color(0xFFF59E0B).withValues(alpha: 0.25),
                      border: Border.all(color: const Color(0xFFF59E0B)),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.workspace_premium_rounded, color: Color(0xFFFDE68A), size: 11),
                        SizedBox(width: 3),
                        Text('Gold Member', style: TextStyle(color: Color(0xFFFDE68A), fontSize: 9, fontWeight: FontWeight.w700)),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 3),
              Text(_user?.email ?? '', style: const TextStyle(color: Colors.white70, fontSize: 12)),
              if (_user?.businessName != null && _user!.businessName!.isNotEmpty) ...[
                const SizedBox(height: 3),
                Text(_user!.businessName!,
                    maxLines: 1, overflow: TextOverflow.ellipsis,
                    style: const TextStyle(color: Color(0xFFFDE68A), fontSize: 12, fontWeight: FontWeight.w600)),
              ],
              if (_user?.chapterName != null) ...[
                const SizedBox(height: 5),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Text(_user!.chapterName!, style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w600)),
                ),
              ],
            ],
          )),
        ],
      ),
    );
  }

  Widget _buildPersonalTab() => SingleChildScrollView(
    padding: const EdgeInsets.all(16),
    child: Column(children: [
      _card('Personal Information', [
        AppTextField(controller: _nameCtrl, label: 'First Name', prefixIcon: Icons.person_outline),
        const SizedBox(height: 12),
        AppTextField(controller: _lastNameCtrl, label: 'Last Name', prefixIcon: Icons.person_outline),
        const SizedBox(height: 12),
        AppTextField(controller: _emailCtrl, label: 'Email', keyboardType: TextInputType.emailAddress, prefixIcon: Icons.email_outlined),
        const SizedBox(height: 12),
        AppTextField(controller: _phoneCtrl, label: 'Phone', keyboardType: TextInputType.phone, prefixIcon: Icons.phone_outlined),
        const SizedBox(height: 12),
        DropdownButtonFormField<String>(
          value: _gender,
          decoration: InputDecoration(labelText: 'Gender', prefixIcon: const Icon(Icons.wc_outlined, size: 20, color: AppTheme.textSecondary), border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
          items: ['Male','Female','Other'].map((g) => DropdownMenuItem(value: g, child: Text(g))).toList(),
          onChanged: (v) => setState(() => _gender = v),
        ),
      ]),
      const SizedBox(height: 12),
      _card('Address', [
        AppTextField(controller: _addressCtrl, label: 'Address', prefixIcon: Icons.location_on_outlined, maxLines: 2),
        const SizedBox(height: 12),
        Row(children: [
          Expanded(child: AppTextField(controller: _cityCtrl, label: 'City', prefixIcon: Icons.location_city_outlined)),
          const SizedBox(width: 10),
          Expanded(child: AppTextField(controller: _stateCtrl, label: 'State')),
        ]),
        const SizedBox(height: 12),
        AppTextField(controller: _pincodeCtrl, label: 'Pincode', keyboardType: TextInputType.number),
      ]),
      const SizedBox(height: 16),
      AppButton(label: 'Save Personal Info', onPressed: _savePersonal, icon: Icons.save_outlined),
    ]),
  );

  Widget _buildProfessionalTab() => SingleChildScrollView(
    padding: const EdgeInsets.all(16),
    child: Column(children: [
      _card('Professional Details', [
        AppTextField(controller: _designationCtrl, label: 'Designation', prefixIcon: Icons.work_outline),
        const SizedBox(height: 12),
        AppTextField(controller: _expCtrl, label: 'Experience (Years)', keyboardType: TextInputType.number),
        const SizedBox(height: 12),
        AppTextField(controller: _skillsCtrl, label: 'Skills', maxLines: 2),
        const SizedBox(height: 12),
        AppTextField(controller: _servicesCtrl, label: 'Services', maxLines: 2),
        const SizedBox(height: 12),
        AppTextField(controller: _bioCtrl, label: 'Bio', maxLines: 4),
      ]),
      const SizedBox(height: 12),
      _card('Social Media', [
        AppTextField(controller: _websiteCtrl, label: 'Website', prefixIcon: Icons.language_outlined, keyboardType: TextInputType.url),
        const SizedBox(height: 12),
        AppTextField(controller: _linkedinCtrl, label: 'LinkedIn', prefixIcon: Icons.link_outlined),
        const SizedBox(height: 12),
        AppTextField(controller: _fbCtrl, label: 'Facebook'),
        const SizedBox(height: 12),
        AppTextField(controller: _igCtrl, label: 'Instagram'),
      ]),
      const SizedBox(height: 16),
      AppButton(label: 'Save Professional Info', onPressed: _saveProfessional, icon: Icons.save_outlined),
    ]),
  );

  Widget _buildBusinessTab() => SingleChildScrollView(
    padding: const EdgeInsets.all(16),
    child: Column(children: [
      _card('Business Information', [
        AppTextField(controller: _bizNameCtrl, label: 'Business Name', prefixIcon: Icons.business_outlined),
        const SizedBox(height: 12),
        AppTextField(controller: _industryCtrl, label: 'Industry/Category'),
        const SizedBox(height: 12),
        AppTextField(controller: _descCtrl, label: 'Description', maxLines: 3),
        const SizedBox(height: 12),
        AppTextField(controller: _gstCtrl, label: 'GST Number'),
      ]),
      const SizedBox(height: 16),
      AppButton(label: 'Save Business Info', onPressed: _saveBusiness, icon: Icons.save_outlined),
    ]),
  );

  Widget _buildAccountTab() => SingleChildScrollView(
    padding: const EdgeInsets.all(16),
    child: Column(children: [
      _card('Change Password', [
        AppTextField(controller: _currPwCtrl, label: 'Current Password', obscureText: true, prefixIcon: Icons.lock_outline),
        const SizedBox(height: 12),
        AppTextField(controller: _newPwCtrl, label: 'New Password', obscureText: true, prefixIcon: Icons.lock_outline),
        const SizedBox(height: 12),
        AppTextField(controller: _confirmPwCtrl, label: 'Confirm Password', obscureText: true, prefixIcon: Icons.lock_outline),
      ]),
      const SizedBox(height: 16),
      AppButton(label: 'Change Password', onPressed: _changePassword, icon: Icons.lock_outline),
      const SizedBox(height: 20),
      AppButton(label: 'Logout', onPressed: _logout, color: AppTheme.error, icon: Icons.logout_rounded),
    ]),
  );

  Widget _card(String title, List<Widget> children) => Container(
    padding: const EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.white,
      borderRadius: BorderRadius.circular(16),
      boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8, offset: const Offset(0, 3))],
    ),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: AppTheme.textPrimary)),
        const SizedBox(height: 14),
        ...children,
      ],
    ),
  );

  void _toast(String msg, {bool error = false}) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(
      content: Text(msg),
      backgroundColor: error ? AppTheme.error : AppTheme.success,
    ));
  }

  Future<void> _pickPhoto() async {
    final picker = ImagePicker();
    final xFile  = await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
    if (xFile == null) return;
    if (!mounted) return;
    final api = context.read<ApiClient>();
    try {
      final resp = await api.uploadProfilePhoto(xFile.path);
      if (!mounted) return;
      if (resp['status'] == 'success') {
        _toast('Profile photo updated!');
        _loadProfile();
      }
    } catch (e) {
      if (!mounted) return;
      _toast(e.toString(), error: true);
    }
  }

  Future<void> _savePersonal() async {
    try {
      final resp = await context.read<ApiClient>().updateProfile({
        'name': _nameCtrl.text, 'last_name': _lastNameCtrl.text,
        'email': _emailCtrl.text, 'phone': _phoneCtrl.text,
        'gender': _gender ?? '', 'address': _addressCtrl.text,
        'city': _cityCtrl.text, 'state': _stateCtrl.text, 'pincode': _pincodeCtrl.text,
      });
      _toast(resp['status'] == 'success' ? 'Personal info saved!' : (resp['message'] ?? 'Failed'), error: resp['status'] != 'success');
    } catch (e) { _toast(e.toString(), error: true); }
  }

  Future<void> _saveProfessional() async {
    if (!mounted) return;
    final api = context.read<ApiClient>();
    try {
      await api.updateSkills({
        'designation': _designationCtrl.text, 'exp_years': _expCtrl.text,
        'skills': _skillsCtrl.text, 'services': _servicesCtrl.text, 'bio': _bioCtrl.text,
      });
      await api.updateSocial({
        'website': _websiteCtrl.text, 'linkedin': _linkedinCtrl.text,
        'facebook': _fbCtrl.text, 'instagram': _igCtrl.text,
      });
      if (!mounted) return;
      _toast('Professional info saved!');
    } catch (e) { if (mounted) _toast(e.toString(), error: true); }
  }

  Future<void> _saveBusiness() async {
    try {
      final resp = await context.read<ApiClient>().updateBusiness({
        'business_name': _bizNameCtrl.text, 'industry': _industryCtrl.text,
        'description': _descCtrl.text, 'gst_number': _gstCtrl.text,
      });
      _toast(resp['status'] == 'success' ? 'Business info saved!' : (resp['message'] ?? 'Failed'), error: resp['status'] != 'success');
    } catch (e) { _toast(e.toString(), error: true); }
  }

  Future<void> _changePassword() async {
    if (_newPwCtrl.text != _confirmPwCtrl.text) {
      _toast('Passwords do not match', error: true); return;
    }
    try {
      final resp = await context.read<ApiClient>().changePassword({
        'current_password': _currPwCtrl.text,
        'new_password': _newPwCtrl.text,
        'confirm_password': _confirmPwCtrl.text,
      });
      _toast(resp['status'] == 'success' ? 'Password changed!' : (resp['message'] ?? 'Failed'), error: resp['status'] != 'success');
      if (resp['status'] == 'success') {
        _currPwCtrl.clear(); _newPwCtrl.clear(); _confirmPwCtrl.clear();
      }
    } catch (e) { _toast(e.toString(), error: true); }
  }

  Future<void> _logout() async {
    await context.read<AuthProvider>().logout();
    if (mounted) context.go('/login');
  }
}
