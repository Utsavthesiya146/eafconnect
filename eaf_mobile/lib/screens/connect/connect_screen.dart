import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class ConnectScreen extends StatefulWidget {
  const ConnectScreen({super.key});
  @override
  State<ConnectScreen> createState() => _ConnectScreenState();
}

class _ConnectScreenState extends State<ConnectScreen> with SingleTickerProviderStateMixin {
  late TabController _tabs;
  List<ReferralModel> _given    = [];
  List<ReferralModel> _received = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _tabs = TabController(length: 2, vsync: this);
    _load();
  }

  @override
  void dispose() {
    _tabs.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getConnects();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        setState(() {
          _given    = (data['given']    as List? ?? []).map((e) => ReferralModel.fromJson(e as Map<String, dynamic>)).toList();
          _received = (data['received'] as List? ?? []).map((e) => ReferralModel.fromJson(e as Map<String, dynamic>)).toList();
        });
      }
    } catch (e) {
      setState(() => _error = e.toString().replaceAll('Exception: ', ''));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Connects / Referrals'),
        bottom: TabBar(
          controller: _tabs,
          labelColor: Colors.white,
          unselectedLabelColor: Colors.white60,
          indicatorColor: AppTheme.secondary,
          tabs: [
            Tab(text: 'Given (${_given.length})'),
            Tab(text: 'Received (${_received.length})'),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showAddDialog,
        backgroundColor: AppTheme.primary,
        icon: const Icon(Icons.add, color: Colors.white),
        label: const Text('Add Referral', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: TabBarView(
                    controller: _tabs,
                    children: [
                      _buildList(_given,    isGiven: true),
                      _buildList(_received, isGiven: false),
                    ],
                  ),
                ),
    );
  }

  Widget _buildList(List<ReferralModel> items, {required bool isGiven}) {
    if (items.isEmpty) {
      return Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        const Icon(Icons.handshake_outlined, size: 54, color: AppTheme.textLight),
        const SizedBox(height: 12),
        Text(isGiven ? 'No referrals given yet' : 'No referrals received yet',
            style: const TextStyle(color: AppTheme.textSecondary)),
      ]));
    }

    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      separatorBuilder: (_, _) => const SizedBox(height: 10),
      itemBuilder: (_, i) => _ReferralCard(referral: items[i], isGiven: isGiven),
    );
  }

  Future<void> _showAddDialog() async {
    List<ChapterMemberModel> members = [];
    try {
      final list = await context.read<ApiClient>().getChapterMembers();
      members = list.map((e) => ChapterMemberModel.fromJson(e as Map<String, dynamic>)).toList();
    } catch (_) {}

    if (!mounted) return;
    await showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (_) => _AddReferralSheet(members: members, onAdded: _load),
    );
  }
}

class _ReferralCard extends StatelessWidget {
  final ReferralModel referral;
  final bool isGiven;
  const _ReferralCard({required this.referral, required this.isGiven});

  Future<void> _makeCall(String phone) async {
    final clean = phone.replaceAll(RegExp(r'[^0-9+]'), '');
    final uri = Uri.parse('tel:$clean');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    }
  }

  Future<void> _openWhatsApp(String phone, String business) async {
    var clean = phone.replaceAll(RegExp(r'[^0-9]'), '');
    if (clean.length == 10) clean = '91$clean';
    final msg = Uri.encodeComponent('Hello, connecting via EAF Connect regarding referral: $business');
    final uri = Uri.parse('https://wa.me/$clean?text=$msg');
    await launchUrl(uri, mode: LaunchMode.externalApplication);
  }

  @override
  Widget build(BuildContext context) {
    final other      = isGiven ? referral.toName   : referral.fromName;
    final otherPhoto = isGiven ? referral.toPhoto  : referral.fromPhoto;
    final otherEmail = isGiven ? referral.toEmail  : referral.fromEmail;
    final currency   = NumberFormat.currency(locale: 'en_IN', symbol: '₹', decimalDigits: 0);
    final phone      = referral.contactNumber;

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
                backgroundImage: otherPhoto != null && otherPhoto.isNotEmpty ? NetworkImage(otherPhoto) : null,
                backgroundColor: AppTheme.primary.withValues(alpha: 0.1),
                child: otherPhoto == null || otherPhoto.isEmpty
                    ? Text(other?.isNotEmpty == true ? other![0].toUpperCase() : '?',
                        style: const TextStyle(color: AppTheme.primary, fontWeight: FontWeight.w700, fontSize: 16))
                    : null,
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      other ?? 'Member',
                      style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: AppTheme.textPrimary),
                    ),
                    if (otherEmail != null && otherEmail.isNotEmpty)
                      Text(otherEmail, style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
                    if (referral.businessType != null && referral.businessType!.isNotEmpty) ...[
                      const SizedBox(height: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFF0F3B68).withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          referral.businessType!,
                          style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFF0F3B68)),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
                    decoration: BoxDecoration(
                      color: isGiven
                          ? const Color(0xFF0F3B68).withValues(alpha: 0.1)
                          : const Color(0xFF10B981).withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          isGiven ? Icons.arrow_outward_rounded : Icons.south_west_rounded,
                          size: 11,
                          color: isGiven ? const Color(0xFF0F3B68) : const Color(0xFF10B981),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          isGiven ? 'Given' : 'Received',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            color: isGiven ? const Color(0xFF0F3B68) : const Color(0xFF10B981),
                          ),
                        ),
                      ],
                    ),
                  ),
                  if (referral.amount > 0) ...[
                    const SizedBox(height: 6),
                    Text(
                      currency.format(referral.amount),
                      style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Color(0xFF10B981)),
                    ),
                  ],
                ],
              ),
            ],
          ),
          if (referral.description != null && referral.description!.trim().isNotEmpty) ...[
            const SizedBox(height: 10),
            Text(
              referral.description!.trim(),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary, height: 1.3),
            ),
          ],
          const SizedBox(height: 10),
          const Divider(height: 1, color: AppTheme.divider),
          const SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (referral.createdAt != null)
                Row(
                  children: [
                    const Icon(Icons.schedule_rounded, size: 13, color: AppTheme.textLight),
                    const SizedBox(width: 4),
                    Text(
                      referral.createdAt!,
                      style: const TextStyle(fontSize: 11, color: AppTheme.textLight),
                    ),
                  ],
                )
              else
                const SizedBox(),
              Row(
                children: [
                  if (phone != null && phone.trim().isNotEmpty) ...[
                    OutlinedButton.icon(
                      onPressed: () => _makeCall(phone),
                      icon: const Icon(Icons.phone_rounded, size: 14, color: Color(0xFF0F3B68)),
                      label: const Text('Call', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: Color(0xFF0F3B68))),
                      style: OutlinedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 0),
                        minimumSize: const Size(0, 32),
                        side: const BorderSide(color: Color(0xFF0F3B68), width: 1.2),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      ),
                    ),
                    const SizedBox(width: 8),
                    ElevatedButton.icon(
                      onPressed: () => _openWhatsApp(phone, referral.businessType ?? 'Business Referral'),
                      icon: const Icon(Icons.chat_bubble_outline_rounded, size: 14, color: Colors.white),
                      label: const Text('WhatsApp', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: Colors.white)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF25D366),
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 0),
                        minimumSize: const Size(0, 32),
                        elevation: 0,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                      ),
                    ),
                  ],
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _AddReferralSheet extends StatefulWidget {
  final List<ChapterMemberModel> members;
  final VoidCallback onAdded;
  const _AddReferralSheet({required this.members, required this.onAdded});

  @override
  State<_AddReferralSheet> createState() => _AddReferralSheetState();
}

class _AddReferralSheetState extends State<_AddReferralSheet> {
  ChapterMemberModel? _selected;
  final _bizTypeCtrl = TextEditingController();
  final _descCtrl    = TextEditingController();
  final _amountCtrl  = TextEditingController();
  bool _loading = false;

  @override
  void dispose() {
    _bizTypeCtrl.dispose(); _descCtrl.dispose(); _amountCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (_selected == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Select a member')));
      return;
    }
    setState(() => _loading = true);
    try {
      final resp = await context.read<ApiClient>().addConnect({
        'to_user': _selected!.userCode,
        'business_type': _bizTypeCtrl.text,
        'description': _descCtrl.text,
        'amount': _amountCtrl.text,
      });
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(resp['status'] == 'success' ? 'Referral added!' : (resp['message'] ?? 'Failed')),
        backgroundColor: resp['status'] == 'success' ? AppTheme.success : AppTheme.error,
      ));
      if (resp['status'] == 'success') widget.onAdded();
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(e.toString()), backgroundColor: AppTheme.error));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.fromLTRB(20, 20, 20, MediaQuery.of(context).viewInsets.bottom + 20),
      child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
        const Text('Add Referral', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
        const SizedBox(height: 16),
        DropdownButtonFormField<ChapterMemberModel>(
          value: _selected,
          hint: const Text('Select Member'),
          isExpanded: true,
          decoration: InputDecoration(prefixIcon: const Icon(Icons.person_outline, size: 20, color: AppTheme.textSecondary), border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
          items: widget.members.map((m) => DropdownMenuItem(
            value: m,
            child: Text(m.name, overflow: TextOverflow.ellipsis),
          )).toList(),
          onChanged: (v) => setState(() => _selected = v),
        ),
        const SizedBox(height: 12),
        AppTextField(controller: _bizTypeCtrl, label: 'Business Type / Category', prefixIcon: Icons.business_outlined),
        const SizedBox(height: 12),
        AppTextField(controller: _descCtrl, label: 'Description', maxLines: 2),
        const SizedBox(height: 12),
        AppTextField(controller: _amountCtrl, label: 'Estimated Value (₹)', keyboardType: TextInputType.number, prefixIcon: Icons.currency_rupee),
        const SizedBox(height: 20),
        AppButton(label: 'Add Referral', onPressed: _loading ? null : _submit, loading: _loading, icon: Icons.send_rounded),
      ]),
    );
  }
}
