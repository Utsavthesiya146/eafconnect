import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';

class NotificationsScreen extends StatefulWidget {
  const NotificationsScreen({super.key});
  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  List<NotificationModel> _notifications = [];
  int _unread = 0;
  bool _loading = true;
  String? _error;

  @override
  void initState() { super.initState(); _load(); }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final resp = await context.read<ApiClient>().getNotifications();
      if (resp['status'] == 'success') {
        final dynamic rawData = resp['data'];
        List<dynamic> notifList = [];
        int unreadCount = 0;
        if (rawData is Map<String, dynamic>) {
          notifList = rawData['notifications'] as List? ?? [];
          unreadCount = (rawData['unread_count'] as num? ?? 0).toInt();
        } else if (rawData is List) {
          notifList = rawData;
          unreadCount = notifList.where((n) {
            if (n is Map) {
              final s = n['status']?.toString();
              return s == '1' || s == 'unread';
            }
            return false;
          }).length;
        }
        setState(() {
          _notifications = notifList
              .map((e) => NotificationModel.fromJson(Map<String, dynamic>.from(e as Map)))
              .toList();
          _unread = unreadCount;
        });
      }
    } catch (e) { setState(() => _error = e.toString().replaceAll('Exception: ', '')); }
    finally { setState(() => _loading = false); }
  }

  Future<void> _markAllRead() async {
    try {
      await context.read<ApiClient>().markAllNotificationsRead();
      _load();
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Notifications${_unread > 0 ? ' ($_unread)' : ''}'),
        actions: [
          if (_unread > 0)
            TextButton(
              onPressed: _markAllRead,
              child: const Text('Read All', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w600)),
            ),
        ],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                  Text(_error!, textAlign: TextAlign.center, style: const TextStyle(color: AppTheme.textSecondary)),
                  const SizedBox(height: 16),
                  AppButton(label: 'Retry', onPressed: _load, width: 120),
                ]))
              : _notifications.isEmpty
                  ? const Center(child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                      Icon(Icons.notifications_off_outlined, size: 54, color: AppTheme.textLight),
                      SizedBox(height: 12),
                      Text('No notifications yet', style: TextStyle(color: AppTheme.textSecondary)),
                    ]))
                  : RefreshIndicator(
                      onRefresh: _load,
                      child: ListView.separated(
                        padding: const EdgeInsets.all(16),
                        itemCount: _notifications.length,
                        separatorBuilder: (_, __) => const SizedBox(height: 8),
                        itemBuilder: (_, i) => _NotificationCard(
                          notification: _notifications[i],
                          onTap: () async {
                            if (_notifications[i].isUnread) {
                              await context.read<ApiClient>().markNotificationRead(_notifications[i].notificationId);
                              _load();
                            }
                          },
                        ),
                      ),
                    ),
    );
  }
}

class _NotificationCard extends StatelessWidget {
  final NotificationModel notification;
  final VoidCallback? onTap;
  const _NotificationCard({required this.notification, this.onTap});

  @override
  Widget build(BuildContext context) {
    final isUnread = notification.isUnread;
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: isUnread ? AppTheme.primary.withValues(alpha: 0.04) : Colors.white,
          borderRadius: BorderRadius.circular(14),
          border: isUnread ? Border.all(color: AppTheme.primary.withValues(alpha: 0.15)) : null,
          boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.04), blurRadius: 6)],
        ),
        child: Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: (isUnread ? AppTheme.primary : AppTheme.textLight).withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(
              isUnread ? Icons.notifications_active_rounded : Icons.notifications_outlined,
              color: isUnread ? AppTheme.primary : AppTheme.textLight, size: 20,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            if (notification.title != null && notification.title!.isNotEmpty)
              Text(notification.title!,
                  style: TextStyle(fontSize: 14, fontWeight: isUnread ? FontWeight.w700 : FontWeight.w500, color: AppTheme.textPrimary)),
            if (notification.message != null && notification.message!.isNotEmpty)
              Padding(padding: const EdgeInsets.only(top: 4),
                child: Text(notification.message!, maxLines: 3, overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 13, color: AppTheme.textSecondary))),
            Padding(padding: const EdgeInsets.only(top: 6),
              child: Row(children: [
                if (notification.chapterName != null) ...[
                  const Icon(Icons.group_outlined, size: 12, color: AppTheme.textLight),
                  const SizedBox(width: 4),
                  Text(notification.chapterName!, style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
                  const SizedBox(width: 10),
                ],
                const Icon(Icons.access_time, size: 12, color: AppTheme.textLight),
                const SizedBox(width: 4),
                Text(notification.createdAt ?? '', style: const TextStyle(fontSize: 11, color: AppTheme.textLight)),
              ])),
          ])),
          if (isUnread)
            Container(
              width: 8, height: 8, margin: const EdgeInsets.only(top: 4),
              decoration: const BoxDecoration(color: AppTheme.primary, shape: BoxShape.circle),
            ),
        ]),
      ),
    );
  }
}
