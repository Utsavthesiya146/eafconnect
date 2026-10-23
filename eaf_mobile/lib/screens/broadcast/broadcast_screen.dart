import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';

class BroadcastScreen extends StatefulWidget {
  const BroadcastScreen({super.key});
  @override
  State<BroadcastScreen> createState() => _BroadcastScreenState();
}

class _BroadcastScreenState extends State<BroadcastScreen> {
  List<BroadcastModel> _broadcasts = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() { super.initState(); _load(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getBroadcasts();
      if (resp['status'] == 'success') {
        final data = resp['data'] as List? ?? [];
        setState(() {
          _broadcasts = data.map((e) => BroadcastModel.fromJson(e as Map<String, dynamic>)).toList();
        });
      }
    } catch (e) { setState(() => _error = e.toString().replaceAll('Exception: ', '')); }
    finally { setState(() => _loading = false); }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Broadcasts')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : _broadcasts.isEmpty
                  ? const Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                      Icon(Icons.campaign_outlined, size: 54, color: AppTheme.textLight),
                      SizedBox(height: 12),
                      Text('No broadcasts yet', style: TextStyle(color: AppTheme.textSecondary)),
                    ]))
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _broadcasts.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 10),
                        itemBuilder: (_, i) => _BroadcastCard(broadcast: _broadcasts[i]),
                      ),
                    ),
    );
  }
}

class _BroadcastCard extends StatelessWidget {
  final BroadcastModel broadcast;
  const _BroadcastCard({required this.broadcast});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white, borderRadius: BorderRadius.circular(14),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 8)],
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: AppTheme.secondary.withValues(alpha: 0.15), borderRadius: BorderRadius.circular(10)),
            child: const Icon(Icons.campaign_rounded, color: AppTheme.secondary, size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text(broadcast.title ?? 'Broadcast', maxLines: 2,
                style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
            if (broadcast.chapterName != null)
              Text(broadcast.chapterName!, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
          ])),
        ]),
        if (broadcast.message != null && broadcast.message!.isNotEmpty) ...[
          const SizedBox(height: 12),
          Text(broadcast.message!, style: const TextStyle(fontSize: 13, color: AppTheme.textSecondary, height: 1.4)),
        ],
        const SizedBox(height: 10),
        Row(children: [
          const Icon(Icons.access_time, size: 12, color: AppTheme.textLight),
          const SizedBox(width: 4),
          Text(broadcast.createdAt ?? '', style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
        ]),
      ]),
    );
  }
}
