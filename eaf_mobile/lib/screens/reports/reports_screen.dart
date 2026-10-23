import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../core/theme/app_theme.dart';

/// Reports screen — hub for all available reports.
/// Links to individual report views.
class ReportsScreen extends StatelessWidget {
  const ReportsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final reports = [
      _ReportItem(icon: Icons.assessment_outlined,      title: 'Weekly Performance', subtitle: 'Your weekly activity summary', color: AppTheme.primary),
      _ReportItem(icon: Icons.handshake_outlined,       title: 'Connects Report', subtitle: 'Referrals given and received', color: AppTheme.success),
      _ReportItem(icon: Icons.currency_rupee_outlined,  title: 'MITB Report', subtitle: 'Money in the Bank transactions', color: AppTheme.warning),
      _ReportItem(icon: Icons.people_outline,           title: 'F2F Report', subtitle: 'One-to-one meetings summary', color: const Color(0xFF9B59B6)),
      _ReportItem(icon: Icons.person_add_outlined,      title: 'Visitors Report', subtitle: 'Visitors brought to chapter', color: const Color(0xFF16A085)),
      _ReportItem(icon: Icons.calendar_month_outlined,  title: 'Attendance Report', subtitle: 'Meeting attendance history', color: AppTheme.info),
      _ReportItem(icon: Icons.group_outlined,           title: 'Chapter Report', subtitle: 'Chapter participation overview', color: AppTheme.primaryDark),
    ];

    return Scaffold(
      appBar: AppBar(title: const Text('Reports')),
      body: ListView.separated(
        padding: const EdgeInsets.all(16),
        itemCount: reports.length,
        separatorBuilder: (_, _) => const SizedBox(height: 10),
        itemBuilder: (_, i) => _ReportTile(item: reports[i]),
      ),
    );
  }
}

class _ReportItem {
  final IconData icon;
  final String title;
  final String subtitle;
  final Color color;
  const _ReportItem({required this.icon, required this.title, required this.subtitle, required this.color});
}

class _ReportTile extends StatelessWidget {
  final _ReportItem item;
  const _ReportTile({required this.item});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8, offset: const Offset(0, 3))],
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        leading: Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(color: item.color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(10)),
          child: Icon(item.icon, color: item.color, size: 22),
        ),
        title: Text(item.title, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
        subtitle: Text(item.subtitle, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
        trailing: const Icon(Icons.chevron_right, color: AppTheme.textLight),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        onTap: () {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(
            content: Text('${item.title} — opening report data from the dashboard...'),
            duration: const Duration(seconds: 2),
          ));
          // Reports use the same data as dashboard/individual screens
          // Navigate to the relevant section
          switch (item.title) {
            case 'Connects Report':    context.go('/connect'); break;
            case 'MITB Report':        context.go('/mitb'); break;
            case 'F2F Report':         context.go('/f2f'); break;
            case 'Visitors Report':    context.go('/visitors'); break;
            case 'Attendance Report':  context.go('/meetings'); break;
            case 'Chapter Report':     context.go('/chapter'); break;
            default:                   context.go('/dashboard'); break;
          }
        },
      ),
    );
  }
}
