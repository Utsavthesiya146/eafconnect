import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';
import '../../widgets/app_text_field.dart';

class F2fScreen extends StatefulWidget {
  const F2fScreen({super.key});
  @override
  State<F2fScreen> createState() => _F2fScreenState();
}

class _F2fScreenState extends State<F2fScreen> with SingleTickerProviderStateMixin {
  late TabController _tabs;
  List<F2fModel> _sent     = [];
  List<F2fModel> _received = [];
  bool _loading = true;
  String? _error;

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
      final resp = await context.read<ApiClient>().getF2f();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        setState(() {
          _sent     = (data['sent']     as List? ?? []).map((e) => F2fModel.fromJson(e as Map<String, dynamic>)).toList();
          _received = (data['received'] as List? ?? []).map((e) => F2fModel.fromJson(e as Map<String, dynamic>)).toList();
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
        title: const Text('Mukha-Mukhi – 1-to-1 Meets'),
        bottom: TabBar(
          controller: _tabs,
          labelColor: Colors.white,
          unselectedLabelColor: Colors.white60,
          indicatorColor: const Color(0xFFD97706),
          indicatorWeight: 3,
          tabs: [Tab(text: 'Sent (${_sent.length})'), Tab(text: 'Received (${_received.length})')],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showAddSheet,
        backgroundColor: const Color(0xFF8B5CF6),
        icon: const Icon(Icons.people_alt_rounded, color: Colors.white),
        label: const Text('Record Mukha-Mukhi', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700)),
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
                  child: TabBarView(controller: _tabs, children: [
                    _buildList(_sent, isSent: true),
                    _buildList(_received, isSent: false),
                  ]),
                ),
    );
  }

  Widget _buildList(List<F2fModel> items, {required bool isSent}) {
    if (items.isEmpty) {
      return Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        const Icon(Icons.people_outline, size: 54, color: AppTheme.textLight),
        const SizedBox(height: 12),
        Text(isSent ? 'No Mukha-Mukhi meetings sent yet' : 'No Mukha-Mukhi meetings received yet',
            style: const TextStyle(color: AppTheme.textSecondary)),
      ]));
    }
    return ListView.separated(
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      separatorBuilder: (_, _) => const SizedBox(height: 10),
      itemBuilder: (_, i) {
        final f = items[i];
        final other      = isSent ? f.toName   : f.fromName;
        final otherPhoto = isSent ? f.toPhoto  : f.fromPhoto;
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
                    backgroundImage: otherPhoto != null && otherPhoto.isNotEmpty && !otherPhoto.contains('empty.png')
                        ? NetworkImage(otherPhoto) : null,
                    backgroundColor: const Color(0xFF8B5CF6).withValues(alpha: 0.12),
                    child: (otherPhoto == null || otherPhoto.isEmpty || otherPhoto.contains('empty.png'))
                        ? Text(other?.isNotEmpty == true ? other![0].toUpperCase() : '?',
                            style: const TextStyle(color: Color(0xFF8B5CF6), fontWeight: FontWeight.w700, fontSize: 16))
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
                        if (f.meetingDate != null && f.meetingDate!.isNotEmpty) ...[
                          const SizedBox(height: 3),
                          Row(
                            children: [
                              const Icon(Icons.event_available_rounded, size: 13, color: Color(0xFFD97706)),
                              const SizedBox(width: 4),
                              Text(
                                f.meetingDate!,
                                style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: Color(0xFFD97706)),
                              ),
                            ],
                          ),
                        ],
                      ],
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 9, vertical: 4),
                    decoration: BoxDecoration(
                      color: isSent
                          ? const Color(0xFF0F3B68).withValues(alpha: 0.1)
                          : const Color(0xFF8B5CF6).withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          isSent ? Icons.arrow_outward_rounded : Icons.south_west_rounded,
                          size: 11,
                          color: isSent ? const Color(0xFF0F3B68) : const Color(0xFF8B5CF6),
                        ),
                        const SizedBox(width: 4),
                        Text(
                          isSent ? 'Sent' : 'Received',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w700,
                            color: isSent ? const Color(0xFF0F3B68) : const Color(0xFF8B5CF6),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              if (f.agenda != null && f.agenda!.trim().isNotEmpty) ...[
                const SizedBox(height: 10),
                Text(
                  f.agenda!.trim(),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary, height: 1.3),
                ),
              ],
              if (f.createdAt != null && f.createdAt!.isNotEmpty) ...[
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.schedule_rounded, size: 12, color: AppTheme.textLight),
                    const SizedBox(width: 4),
                    Text(f.createdAt!, style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
                  ],
                ),
              ],
            ],
          ),
        );
      },
    );
  }

  Future<void> _showAddSheet() async {
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
      builder: (_) => _AddF2fSheet(members: members, onAdded: _load),
    );
  }
}

class _AddF2fSheet extends StatefulWidget {
  final List<ChapterMemberModel> members;
  final VoidCallback onAdded;
  const _AddF2fSheet({required this.members, required this.onAdded});
  @override
  State<_AddF2fSheet> createState() => _AddF2fSheetState();
}

class _AddF2fSheetState extends State<_AddF2fSheet> {
  ChapterMemberModel? _selected;
  final _agendaCtrl   = TextEditingController();
  final _locationCtrl = TextEditingController();
  final _notesCtrl    = TextEditingController();
  final _dateCtrl     = TextEditingController();
  bool _loading = false;

  @override
  void dispose() { _agendaCtrl.dispose(); _locationCtrl.dispose(); _notesCtrl.dispose(); _dateCtrl.dispose(); super.dispose(); }

  Future<void> _pickDate() async {
    final d = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now().subtract(const Duration(days: 365)),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (d != null) _dateCtrl.text = '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';
  }

  Future<void> _submit() async {
    if (_selected == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Select a member'))); return;
    }
    setState(() => _loading = true);
    try {
      final resp = await context.read<ApiClient>().addF2f({
        'to_user': _selected!.userCode,
        'meeting_date': _dateCtrl.text,
        'agenda': _agendaCtrl.text,
        'location': _locationCtrl.text,
        'notes': _notesCtrl.text,
      });
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(resp['status'] == 'success' ? 'F2F recorded!' : (resp['message'] ?? 'Failed')),
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
      const Text('Record F2F Meeting', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
      const SizedBox(height: 16),
      DropdownButtonFormField<ChapterMemberModel>(
        value: _selected, hint: const Text('Select Member'), isExpanded: true,
        decoration: InputDecoration(border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
        items: widget.members.map((m) => DropdownMenuItem(value: m, child: Text(m.name))).toList(),
        onChanged: (v) => setState(() => _selected = v),
      ),
      const SizedBox(height: 12),
      GestureDetector(
        onTap: _pickDate,
        child: AbsorbPointer(child: AppTextField(controller: _dateCtrl, label: 'Meeting Date', prefixIcon: Icons.calendar_today_outlined)),
      ),
      const SizedBox(height: 12),
      AppTextField(controller: _agendaCtrl, label: 'Agenda', maxLines: 2),
      const SizedBox(height: 12),
      AppTextField(controller: _locationCtrl, label: 'Location', prefixIcon: Icons.location_on_outlined),
      const SizedBox(height: 12),
      AppTextField(controller: _notesCtrl, label: 'Notes', maxLines: 2),
      const SizedBox(height: 20),
      AppButton(label: 'Submit', onPressed: _loading ? null : _submit, loading: _loading, icon: Icons.check_circle_outline),
    ]),
  );
}
