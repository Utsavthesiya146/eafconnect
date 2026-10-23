import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';

class ChapterScreen extends StatefulWidget {
  const ChapterScreen({super.key});
  @override
  State<ChapterScreen> createState() => _ChapterScreenState();
}

class _ChapterScreenState extends State<ChapterScreen> {
  Map<String, dynamic>? _chapter;
  String _myRole = '';
  List<ChapterMemberModel> _members = [];
  int _memberCount = 0;
  double _totalMitb = 0;
  bool _loading = true;
  String? _error;
  String _search = '';

  @override
  void initState() { super.initState(); _load(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getChapter();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>?;
        if (data == null) {
          setState(() { _error = 'You are not a member of any chapter.'; });
          return;
        }
        setState(() {
          _chapter     = data['chapter'] as Map<String, dynamic>?;
          _myRole      = data['my_role']?.toString() ?? '';
          _memberCount = (data['member_count'] as num? ?? 0).toInt();
          _totalMitb   = (data['total_mitb']   as num? ?? 0).toDouble();
          _members     = (data['members'] as List? ?? [])
              .map((e) => ChapterMemberModel.fromJson(e as Map<String, dynamic>)).toList();
        });
      }
    } catch (e) { setState(() => _error = e.toString().replaceAll('Exception: ', '')); }
    finally { setState(() => _loading = false); }
  }

  List<ChapterMemberModel> get _filtered =>
      _search.isEmpty ? _members : _members.where((m) =>
          m.name.toLowerCase().contains(_search.toLowerCase()) ||
          (m.businessName?.toLowerCase().contains(_search.toLowerCase()) ?? false) ||
          (m.designation?.toLowerCase().contains(_search.toLowerCase()) ?? false)
      ).toList();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Chapter')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  const Icon(Icons.group_off_outlined, size: 54, color: AppTheme.textLight),
                  const SizedBox(height: 12),
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : RefreshIndicator(
                  onRefresh: _load,
                  child: CustomScrollView(slivers: [
                    // Chapter info header
                    SliverToBoxAdapter(child: _buildChapterHeader()),

                    // Search bar
                    SliverToBoxAdapter(child: Padding(
                      padding: const EdgeInsets.fromLTRB(16, 0, 16, 8),
                      child: TextField(
                        decoration: InputDecoration(
                          hintText: 'Search members...',
                          prefixIcon: const Icon(Icons.search, color: AppTheme.textSecondary),
                          filled: true, fillColor: Colors.white,
                          border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
                          contentPadding: const EdgeInsets.symmetric(vertical: 0, horizontal: 16),
                        ),
                        onChanged: (v) => setState(() => _search = v),
                      ),
                    )),

                    // Members list
                    SliverToBoxAdapter(child: Padding(
                      padding: const EdgeInsets.fromLTRB(16, 0, 16, 4),
                      child: Text('Members (${_filtered.length})',
                          style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700)),
                    )),

                    SliverList(delegate: SliverChildBuilderDelegate(
                      (_, i) => _MemberTile(member: _filtered[i]),
                      childCount: _filtered.length,
                    )),

                    const SliverToBoxAdapter(child: SizedBox(height: 100)),
                  ]),
                ),
    );
  }

  Widget _buildChapterHeader() {
    final name = _chapter?['chapter_name']?.toString() ?? 'My Chapter';
    final currency = '₹${(_totalMitb / 1000).toStringAsFixed(0)}K';
    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF0F3B68), Color(0xFF1E293B)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: const Color(0xFFD97706).withValues(alpha: 0.3)),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF0F3B68).withValues(alpha: 0.2),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.white.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(14),
              border: Border.all(color: const Color(0xFFF59E0B), width: 1.5),
            ),
            child: const Icon(Icons.corporate_fare_rounded, color: Color(0xFFFDE68A), size: 28),
          ),
          const SizedBox(width: 14),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text(name, style: const TextStyle(color: Colors.white, fontSize: 19, fontWeight: FontWeight.w800)),
            const SizedBox(height: 3),
            Row(
              children: [
                if (_myRole.isNotEmpty)
                  Container(
                    margin: const EdgeInsets.only(right: 6),
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: const Color(0xFFD97706),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(_myRole, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Colors.white)),
                  ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: const Text('KVV Chapter Standard', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: Color(0xFFCBD5E1))),
                ),
              ],
            ),
          ])),
        ]),
        const SizedBox(height: 18),
        Container(
          padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
          decoration: BoxDecoration(
            color: Colors.black.withValues(alpha: 0.15),
            borderRadius: BorderRadius.circular(14),
          ),
          child: Row(children: [
            Expanded(child: _chapterStat('Active Members', '$_memberCount', Icons.people_alt_rounded)),
            Container(width: 1, height: 38, color: Colors.white24),
            Expanded(child: _chapterStat('Chapter MITB Closed', currency, Icons.currency_rupee_rounded)),
          ]),
        ),
      ]),
    );
  }

  Widget _chapterStat(String label, String value, IconData icon) => Column(children: [
    Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(icon, color: const Color(0xFFFDE68A), size: 16),
        const SizedBox(width: 6),
        Text(value, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w800)),
      ],
    ),
    const SizedBox(height: 2),
    Text(label, style: const TextStyle(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.w500)),
  ]);
}

class _MemberTile extends StatelessWidget {
  final ChapterMemberModel member;
  const _MemberTile({required this.member});

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
    final msg = Uri.encodeComponent('Hello $name, connecting via EAF Connect Chapter Networking!');
    final uri = Uri.parse('https://wa.me/$clean?text=$msg');
    await launchUrl(uri, mode: LaunchMode.externalApplication);
  }

  @override
  Widget build(BuildContext context) {
    final phone = member.phone;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 5),
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
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: const Color(0xFFF59E0B), width: 1.5),
                ),
                child: CircleAvatar(
                  radius: 24,
                  backgroundImage: member.photoUrl != null && member.photoUrl!.isNotEmpty && !member.photoUrl!.contains('empty.png')
                      ? NetworkImage(member.photoUrl!) : null,
                  backgroundColor: AppTheme.primary.withValues(alpha: 0.1),
                  child: (member.photoUrl == null || member.photoUrl!.isEmpty || member.photoUrl!.contains('empty.png'))
                      ? Text(member.name.isNotEmpty ? member.name[0].toUpperCase() : '?',
                          style: const TextStyle(color: AppTheme.primary, fontWeight: FontWeight.w700, fontSize: 16)) : null,
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Flexible(
                          child: Text(
                            member.name,
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: AppTheme.textPrimary),
                          ),
                        ),
                        if (member.memberRole != null && member.memberRole!.isNotEmpty) ...[
                          const SizedBox(width: 6),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                            decoration: BoxDecoration(
                              color: const Color(0xFF0F3B68).withValues(alpha: 0.08),
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: Text(
                              member.memberRole!,
                              style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Color(0xFF0F3B68)),
                            ),
                          ),
                        ],
                      ],
                    ),
                    if (member.designation != null && member.designation!.isNotEmpty) ...[
                      const SizedBox(height: 2),
                      Text(member.designation!, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary, fontWeight: FontWeight.w500)),
                    ],
                    if (member.businessName != null && member.businessName!.isNotEmpty) ...[
                      const SizedBox(height: 3),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFFD97706).withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          member.businessName!,
                          style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFFB45309)),
                        ),
                      ),
                    ],
                  ],
                ),
              ),
            ],
          ),
          if (phone != null && phone.trim().isNotEmpty) ...[
            const SizedBox(height: 10),
            const Divider(height: 1, color: AppTheme.divider),
            const SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
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
                const SizedBox(width: 8),
                ElevatedButton.icon(
                  onPressed: () => _openWhatsApp(phone, member.name),
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
        ],
      ),
    );
  }
}
