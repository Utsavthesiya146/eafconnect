import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class MitbScreen extends StatefulWidget {
  const MitbScreen({super.key});
  @override
  State<MitbScreen> createState() => _MitbScreenState();
}

class _MitbScreenState extends State<MitbScreen> with SingleTickerProviderStateMixin {
  late TabController _tabs;
  List<MitbModel> _given    = [];
  List<MitbModel> _received = [];
  double _totalGiven = 0, _totalReceived = 0;
  bool _loading = true;
  String? _error;

  final _currency = NumberFormat.currency(locale: 'en_IN', symbol: '₹', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _tabs = TabController(length: 2, vsync: this);
    _load();
  }

  @override
  void dispose() { _tabs.dispose(); super.dispose(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getMitb();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        setState(() {
          _given        = (data['given']    as List? ?? []).map((e) => MitbModel.fromJson(e as Map<String, dynamic>)).toList();
          _received     = (data['received'] as List? ?? []).map((e) => MitbModel.fromJson(e as Map<String, dynamic>)).toList();
          _totalGiven    = (data['total_given']    as num? ?? 0).toDouble();
          _totalReceived = (data['total_received'] as num? ?? 0).toDouble();
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
        title: const Text('MITB – Closed Business'),
        bottom: TabBar(
          controller: _tabs,
          labelColor: Colors.white,
          unselectedLabelColor: Colors.white60,
          indicatorColor: const Color(0xFFD97706),
          indicatorWeight: 3,
          tabs: [Tab(text: 'Given (${_given.length})'), Tab(text: 'Received (${_received.length})')],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showAddDialog,
        backgroundColor: const Color(0xFF0F3B68),
        icon: const Icon(Icons.workspace_premium_rounded, color: Color(0xFFFDE68A)),
        label: const Text('Say Thank You', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
      ),
      body: Column(
        children: [
          // Summary bar (EAF Standard)
          if (!_loading && _error == null)
            Container(
              margin: const EdgeInsets.fromLTRB(16, 12, 16, 4),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.withValues(alpha: 0.15)),
                boxShadow: [
                  BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 10, offset: const Offset(0, 3)),
                ],
              ),
              child: Row(children: [
                Expanded(child: _summaryItem('Total Business Given', _currency.format(_totalGiven), const Color(0xFF0F3B68), Icons.arrow_outward_rounded)),
                Container(width: 1, height: 42, color: AppTheme.divider),
                Expanded(child: _summaryItem('Total Business Received', _currency.format(_totalReceived), const Color(0xFF10B981), Icons.south_west_rounded)),
              ]),
            ),

          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _error != null
                    ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                        Text(_error!, textAlign: TextAlign.center),
                        const SizedBox(height: 12),
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
          ),
        ],
      ),
    );
  }

  Widget _summaryItem(String label, String value, Color color, IconData icon) => Column(children: [
    Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(icon, size: 14, color: color),
        const SizedBox(width: 4),
        Text(value, style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: color)),
      ],
    ),
    const SizedBox(height: 3),
    Text(label, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: AppTheme.textSecondary)),
  ]);

  Widget _buildList(List<MitbModel> items, {required bool isGiven}) {
    if (items.isEmpty) {
      return Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        const Icon(Icons.currency_rupee_outlined, size: 54, color: AppTheme.textLight),
        const SizedBox(height: 12),
        Text(isGiven ? 'No MITB given yet' : 'No MITB received yet',
            style: const TextStyle(color: AppTheme.textSecondary)),
      ]));
    }

    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      separatorBuilder: (_, _) => const SizedBox(height: 10),
      itemBuilder: (_, i) => _MitbCard(mitb: items[i], isGiven: isGiven, currency: _currency),
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
      builder: (_) => _AddMitbSheet(members: members, onAdded: _load),
    );
  }
}

class _MitbCard extends StatelessWidget {
  final MitbModel mitb;
  final bool isGiven;
  final NumberFormat currency;
  const _MitbCard({required this.mitb, required this.isGiven, required this.currency});

  @override
  Widget build(BuildContext context) {
    final other      = isGiven ? mitb.toName   : mitb.fromName;
    final otherPhoto = isGiven ? mitb.toPhoto  : mitb.fromPhoto;
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
                    if (mitb.businessType != null && mitb.businessType!.isNotEmpty) ...[
                      const SizedBox(height: 3),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFF0F3B68).withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          mitb.businessType!,
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
                  Text(
                    currency.format(mitb.amount),
                    style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w800, color: Color(0xFF10B981)),
                  ),
                  const SizedBox(height: 4),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
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
                ],
              ),
            ],
          ),
          if (mitb.description != null && mitb.description!.trim().isNotEmpty) ...[
            const SizedBox(height: 8),
            Text(
              mitb.description!.trim(),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary),
            ),
          ],
          if (mitb.createdAt != null && mitb.createdAt!.isNotEmpty) ...[
            const SizedBox(height: 8),
            Row(
              children: [
                const Icon(Icons.schedule_rounded, size: 12, color: AppTheme.textLight),
                const SizedBox(width: 4),
                Text(mitb.createdAt!, style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
              ],
            ),
          ],
        ],
      ),
    );
  }
}

class _AddMitbSheet extends StatefulWidget {
  final List<ChapterMemberModel> members;
  final VoidCallback onAdded;
  const _AddMitbSheet({required this.members, required this.onAdded});

  @override
  State<_AddMitbSheet> createState() => _AddMitbSheetState();
}

class _AddMitbSheetState extends State<_AddMitbSheet> {
  ChapterMemberModel? _selected;
  final _amountCtrl   = TextEditingController();
  final _bizTypeCtrl  = TextEditingController();
  final _descCtrl     = TextEditingController();
  bool _loading = false;

  @override
  void dispose() { _amountCtrl.dispose(); _bizTypeCtrl.dispose(); _descCtrl.dispose(); super.dispose(); }

  Future<void> _submit() async {
    if (_selected == null || _amountCtrl.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Select member and enter amount'))); return;
    }
    setState(() => _loading = true);
    try {
      final resp = await context.read<ApiClient>().addMitb({
        'to_user': _selected!.userCode,
        'amount': _amountCtrl.text,
        'business_type': _bizTypeCtrl.text,
        'description': _descCtrl.text,
      });
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(resp['status'] == 'success' ? 'MITB added!' : (resp['message'] ?? 'Failed')),
        backgroundColor: resp['status'] == 'success' ? AppTheme.success : AppTheme.error,
      ));
      if (resp['status'] == 'success') widget.onAdded();
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString()), backgroundColor: AppTheme.error));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) => Padding(
    padding: EdgeInsets.fromLTRB(20, 20, 20, MediaQuery.of(context).viewInsets.bottom + 20),
    child: Column(mainAxisSize: MainAxisSize.min, crossAxisAlignment: CrossAxisAlignment.start, children: [
      const Text('Add MITB Record', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
      const SizedBox(height: 16),
      DropdownButtonFormField<ChapterMemberModel>(
        value: _selected,
        hint: const Text('Select Member'),
        isExpanded: true,
        decoration: InputDecoration(border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
        items: widget.members.map((m) => DropdownMenuItem(value: m, child: Text(m.name))).toList(),
        onChanged: (v) => setState(() => _selected = v),
      ),
      const SizedBox(height: 12),
      AppTextField(controller: _amountCtrl, label: 'Amount (₹)', keyboardType: TextInputType.number, prefixIcon: Icons.currency_rupee),
      const SizedBox(height: 12),
      AppTextField(controller: _bizTypeCtrl, label: 'Business Type', prefixIcon: Icons.business_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _descCtrl, label: 'Description', maxLines: 2),
      const SizedBox(height: 20),
      AppButton(label: 'Submit', onPressed: _loading ? null : _submit, loading: _loading, icon: Icons.check_circle_outline),
    ]),
  );
}

