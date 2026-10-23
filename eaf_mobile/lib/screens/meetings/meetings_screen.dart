import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';

class MeetingsScreen extends StatefulWidget {
  const MeetingsScreen({super.key});
  @override
  State<MeetingsScreen> createState() => _MeetingsScreenState();
}

class _MeetingsScreenState extends State<MeetingsScreen> {
  List<MeetingModel> _meetings = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getMeetings();
      if (resp['status'] == 'success') {
        final data = resp['data'] as Map<String, dynamic>;
        setState(() {
          _meetings = (data['meetings'] as List? ?? [])
              .map((e) => MeetingModel.fromJson(e as Map<String, dynamic>))
              .toList();
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
      appBar: AppBar(title: const Text('Meetings & Attendance')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  const Icon(Icons.wifi_off_rounded, size: 48, color: AppTheme.textLight),
                  const SizedBox(height: 12),
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120, icon: Icons.refresh),
                ]))
              : _meetings.isEmpty
                  ? const Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                      Icon(Icons.calendar_month_outlined, size: 54, color: AppTheme.textLight),
                      SizedBox(height: 12),
                      Text('No meetings scheduled', style: TextStyle(color: AppTheme.textSecondary)),
                    ]))
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _meetings.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 12),
                        itemBuilder: (_, i) => _MeetingCard(meeting: _meetings[i]),
                      ),
                    ),
    );
  }
}

class _MeetingCard extends StatelessWidget {
  final MeetingModel meeting;
  const _MeetingCard({required this.meeting});

  @override
  Widget build(BuildContext context) {
    final isUpcoming = meeting.date != null && DateTime.tryParse(meeting.date!)?.isAfter(DateTime.now()) == true;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: isUpcoming ? Border.all(color: AppTheme.primary.withValues(alpha: 0.3), width: 1.5) : null,
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8, offset: const Offset(0, 3))],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: (isUpcoming ? AppTheme.primary : AppTheme.textLight).withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(Icons.event_rounded, color: isUpcoming ? AppTheme.primary : AppTheme.textLight, size: 22),
          ),
          const SizedBox(width: 12),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text(meeting.title ?? 'Chapter Meeting',
                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600, color: AppTheme.textPrimary)),
            if (meeting.chapterName != null)
              Text(meeting.chapterName!, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
          ])),
          _attendanceBadge(meeting.myAttendance),
        ]),
        const SizedBox(height: 12),
        const Divider(height: 1),
        const SizedBox(height: 12),
        Row(children: [
          _infoItem(Icons.calendar_today_outlined, meeting.date ?? 'TBD'),
          const SizedBox(width: 20),
          _infoItem(Icons.access_time_outlined, meeting.time ?? 'TBD'),
        ]),
        if (meeting.venue != null && meeting.venue!.isNotEmpty) ...[
          const SizedBox(height: 8),
          _infoItem(Icons.location_on_outlined, meeting.venue!),
        ],
        if (meeting.description != null && meeting.description!.isNotEmpty) ...[
          const SizedBox(height: 10),
          Text(meeting.description!, maxLines: 3, overflow: TextOverflow.ellipsis,
              style: const TextStyle(fontSize: 13, color: AppTheme.textSecondary)),
        ],
      ]),
    );
  }

  Widget _infoItem(IconData icon, String text) => Row(children: [
    Icon(icon, size: 14, color: AppTheme.textSecondary),
    const SizedBox(width: 4),
    Flexible(child: Text(text, style: const TextStyle(fontSize: 13, color: AppTheme.textSecondary))),
  ]);

  Widget _attendanceBadge(String? attendance) {
    Color c;
    String label;
    switch (attendance?.toLowerCase()) {
      case 'present':  c = AppTheme.success; label = 'Present'; break;
      case 'absent':   c = AppTheme.error;   label = 'Absent';  break;
      case 'late':     c = AppTheme.warning; label = 'Late';    break;
      default:         c = AppTheme.textLight; label = attendance ?? 'N/A';
    }
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: c.withValues(alpha: 0.12), borderRadius: BorderRadius.circular(8)),
      child: Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: c)),
    );
  }
}
