import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class InvitationsScreen extends StatefulWidget {
  const InvitationsScreen({super.key});
  @override
  State<InvitationsScreen> createState() => _InvitationsScreenState();
}

class _InvitationsScreenState extends State<InvitationsScreen> {
  List<Map<String, dynamic>> _invitations = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() { super.initState(); _load(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getInvitations();
      if (resp['status'] == 'success') {
        setState(() {
          _invitations = (resp['data'] as List? ?? [])
              .map((e) => e as Map<String, dynamic>).toList();
        });
      }
    } catch (e) { setState(() => _error = e.toString().replaceAll('Exception: ', '')); }
    finally { setState(() => _loading = false); }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Invitations')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showAddSheet,
        backgroundColor: AppTheme.primary,
        icon: const Icon(Icons.mail_outline, color: Colors.white),
        label: const Text('Invite', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : _invitations.isEmpty
                  ? const Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                      Icon(Icons.mail_outline, size: 54, color: AppTheme.textLight),
                      SizedBox(height: 12),
                      Text('No invitations sent', style: TextStyle(color: AppTheme.textSecondary)),
                    ]))
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _invitations.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 10),
                        itemBuilder: (_, i) {
                          final inv = _invitations[i];
                          return Container(
                            padding: const EdgeInsets.all(14),
                            decoration: BoxDecoration(
                              color: Colors.white, borderRadius: BorderRadius.circular(14),
                              boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8)],
                            ),
                            child: Row(children: [
                              CircleAvatar(radius: 22, backgroundColor: AppTheme.info.withValues(alpha: 0.1),
                                child: const Icon(Icons.mail_outline, color: AppTheme.info, size: 20)),
                              const SizedBox(width: 12),
                              Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                                Text(inv['name']?.toString() ?? 'Guest',
                                    style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
                                if (inv['email'] != null)
                                  Text(inv['email'].toString(), style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
                                if (inv['phone'] != null)
                                  Text(inv['phone'].toString(), style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
                                Text(inv['created_at']?.toString() ?? '', style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
                              ])),
                              _badge(inv['status']?.toString() ?? 'Pending'),
                            ]),
                          );
                        },
                      ),
                    ),
    );
  }

  Widget _badge(String status) {
    Color c;
    switch (status.toLowerCase()) {
      case 'accepted': case 'joined': c = AppTheme.success; break;
      case 'rejected': c = AppTheme.error; break;
      default: c = AppTheme.warning;
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(color: c.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(6)),
      child: Text(status, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: c)),
    );
  }

  Future<void> _showAddSheet() async {
    await showModalBottomSheet(
      context: context, isScrollControlled: true, backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (_) => _AddInvitationSheet(onAdded: _load),
    );
  }
}

class _AddInvitationSheet extends StatefulWidget {
  final VoidCallback onAdded;
  const _AddInvitationSheet({required this.onAdded});
  @override
  State<_AddInvitationSheet> createState() => _AddInvitationSheetState();
}

class _AddInvitationSheetState extends State<_AddInvitationSheet> {
  final _nameCtrl  = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  bool _loading = false;

  @override
  void dispose() { _nameCtrl.dispose(); _emailCtrl.dispose(); _phoneCtrl.dispose(); super.dispose(); }

  Future<void> _submit() async {
    if (_nameCtrl.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Name is required'))); return;
    }
    setState(() => _loading = true);
    try {
      final resp = await context.read<ApiClient>().addInvitation({
        'name': _nameCtrl.text.trim(),
        'email': _emailCtrl.text.trim(),
        'phone': _phoneCtrl.text.trim(),
      });
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(resp['status'] == 'success' ? 'Invitation sent!' : (resp['message'] ?? 'Failed')),
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
      const Text('Send Invitation', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
      const SizedBox(height: 16),
      AppTextField(controller: _nameCtrl, label: 'Full Name *', prefixIcon: Icons.person_outline),
      const SizedBox(height: 12),
      AppTextField(controller: _emailCtrl, label: 'Email', keyboardType: TextInputType.emailAddress, prefixIcon: Icons.email_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _phoneCtrl, label: 'Phone', keyboardType: TextInputType.phone, prefixIcon: Icons.phone_outlined),
      const SizedBox(height: 20),
      AppButton(label: 'Send Invitation', onPressed: _loading ? null : _submit, loading: _loading, icon: Icons.send),
    ]),
  );
}
