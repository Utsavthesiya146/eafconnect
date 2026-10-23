import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class VisitorsScreen extends StatefulWidget {
  const VisitorsScreen({super.key});
  @override
  State<VisitorsScreen> createState() => _VisitorsScreenState();
}

class _VisitorsScreenState extends State<VisitorsScreen> {
  List<VisitorModel> _visitors = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() { super.initState(); _load(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getVisitors();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        setState(() {
          _visitors = (data['visitors'] as List? ?? [])
              .map((e) => VisitorModel.fromJson(e as Map<String, dynamic>)).toList();
        });
      }
    } catch (e) { setState(() => _error = e.toString().replaceAll('Exception: ', '')); }
    finally { setState(() => _loading = false); }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Visitor Tracking & Invitations')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showAddSheet,
        backgroundColor: const Color(0xFF0F3B68),
        icon: const Icon(Icons.person_add_rounded, color: Color(0xFFFDE68A)),
        label: const Text('Invite Visitor', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : _visitors.isEmpty
                  ? const Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                      Icon(Icons.person_add_outlined, size: 54, color: AppTheme.textLight),
                      SizedBox(height: 12),
                      Text('No visitors recorded', style: TextStyle(color: AppTheme.textSecondary)),
                    ]))
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _visitors.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 10),
                        itemBuilder: (_, i) => _VisitorCard(visitor: _visitors[i]),
                      ),
                    ),
    );
  }

  Future<void> _showAddSheet() async {
    await showModalBottomSheet(
      context: context, isScrollControlled: true, backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (_) => _AddVisitorSheet(onAdded: _load),
    );
  }
}

class _VisitorCard extends StatelessWidget {
  final VisitorModel visitor;
  const _VisitorCard({required this.visitor});

  Future<void> _makeCall(String phone) async {
    final clean = phone.replaceAll(RegExp(r'[^0-9+]'), '');
    final uri = Uri.parse('tel:$clean');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    }
  }

  Future<void> _openWhatsApp(String phone, String name) async {
    var clean = phone.replaceAll(RegExp(r'[^0-9]'), '');
    if (clean.length == 10) clean = '91$clean';
    final msg = Uri.encodeComponent('Hello $name, inviting you to our upcoming EAF Connect Chapter Networking Meeting!');
    final uri = Uri.parse('https://wa.me/$clean?text=$msg');
    await launchUrl(uri, mode: LaunchMode.externalApplication);
  }

  @override
  Widget build(BuildContext context) {
    final phone = visitor.phone;

    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.withValues(alpha: 0.15)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.04),
            blurRadius: 10,
            offset: const Offset(0, 3),
          )
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              CircleAvatar(
                radius: 24,
                backgroundColor: const Color(0xFF0F3B68).withValues(alpha: 0.1),
                child: Text(
                  visitor.name.isNotEmpty ? visitor.name[0].toUpperCase() : '?',
                  style: const TextStyle(color: Color(0xFF0F3B68), fontWeight: FontWeight.w700, fontSize: 16),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      visitor.name,
                      style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: AppTheme.textPrimary),
                    ),
                    if (visitor.company != null && visitor.company!.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(visitor.company!, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary, fontWeight: FontWeight.w500)),
                    ],
                    if (visitor.business != null && visitor.business!.isNotEmpty) ...[
                      const SizedBox(height: 3),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFF0F3B68).withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          visitor.business!,
                          style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF0F3B68)),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              _statusBadge(visitor.status),
            ],
          ),
          const SizedBox(height: 10),
          const Divider(height: 1, color: AppTheme.divider),
          const SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (visitor.createdAt != null && visitor.createdAt!.isNotEmpty)
                Row(
                  children: [
                    const Icon(Icons.schedule_rounded, size: 12, color: AppTheme.textLight),
                    const SizedBox(width: 4),
                    Text(visitor.createdAt!, style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
                  ],
                )
              else
                const SizedBox(),
              if (phone != null && phone.trim().isNotEmpty)
                Row(
                  children: [
                    OutlinedButton.icon(
                      onPressed: () => _makeCall(phone),
                      icon: const Icon(Icons.phone_rounded, size: 13, color: Color(0xFF0F3B68)),
                      label: const Text('Call', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF0F3B68))),
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 0),
                        minimumSize: const Size(0, 30),
                        side: const BorderSide(color: Color(0xFF0F3B68), width: 1.2),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      ),
                    ),
                    const SizedBox(width: 6),
                    ElevatedButton.icon(
                      onPressed: () => _openWhatsApp(phone, visitor.name),
                      icon: const Icon(Icons.chat_bubble_outline_rounded, size: 13, color: Colors.white),
                      label: const Text('WhatsApp', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Colors.white)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF25D366),
                        padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 0),
                        minimumSize: const Size(0, 30),
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      ),
                    ),
                  ],
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _statusBadge(String status) {
    Color c;
    switch (status.toLowerCase()) {
      case 'approved':
      case 'joined': c = AppTheme.success; break;
      case 'rejected': c = AppTheme.error; break;
      default: c = const Color(0xFFD97706);
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(color: c.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(20)),
      child: Text(status, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: c)),
    );
  }
}

class _AddVisitorSheet extends StatefulWidget {
  final VoidCallback onAdded;
  const _AddVisitorSheet({required this.onAdded});
  @override
  State<_AddVisitorSheet> createState() => _AddVisitorSheetState();
}

class _AddVisitorSheetState extends State<_AddVisitorSheet> {
  final _nameCtrl     = TextEditingController();
  final _emailCtrl    = TextEditingController();
  final _phoneCtrl    = TextEditingController();
  final _businessCtrl = TextEditingController();
  final _purposeCtrl  = TextEditingController();
  bool _loading = false;

  @override
  void dispose() {
    _nameCtrl.dispose(); _emailCtrl.dispose(); _phoneCtrl.dispose();
    _businessCtrl.dispose(); _purposeCtrl.dispose(); super.dispose();
  }

  Future<void> _submit() async {
    if (_nameCtrl.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Name is required'))); return;
    }
    setState(() => _loading = true);
    try {
      final resp = await context.read<ApiClient>().addVisitor({
        'name': _nameCtrl.text.trim(),
        'email': _emailCtrl.text.trim(),
        'phone': _phoneCtrl.text.trim(),
        'business': _businessCtrl.text.trim(),
        'purpose': _purposeCtrl.text.trim(),
      });
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(resp['status'] == 'success' ? 'Visitor added!' : (resp['message'] ?? 'Failed')),
        backgroundColor: resp['status'] == 'success' ? AppTheme.success : AppTheme.error,
      ));
      if (resp['status'] == 'success') widget.onAdded();
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString()), backgroundColor: AppTheme.error));
    } finally { if (mounted) setState(() => _loading = false); }
  }

  @override
  Widget build(BuildContext context) => Padding(
    padding: EdgeInsets.fromLTRB(20, 20, 20, MediaQuery.of(context).viewInsets.bottom + 20),
    child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
      const Text('Add Visitor', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
      const SizedBox(height: 16),
      AppTextField(controller: _nameCtrl, label: 'Visitor Name *', prefixIcon: Icons.person_outline),
      const SizedBox(height: 12),
      AppTextField(controller: _phoneCtrl, label: 'Phone', keyboardType: TextInputType.phone, prefixIcon: Icons.phone_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _emailCtrl, label: 'Email', keyboardType: TextInputType.emailAddress, prefixIcon: Icons.email_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _businessCtrl, label: 'Business / Company', prefixIcon: Icons.business_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _purposeCtrl, label: 'Purpose of Visit', maxLines: 2),
      const SizedBox(height: 20),
      AppButton(label: 'Add Visitor', onPressed: _loading ? null : _submit, loading: _loading, icon: Icons.person_add),
    ]),
  );
}
