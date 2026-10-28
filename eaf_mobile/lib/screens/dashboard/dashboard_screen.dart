import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:intl/intl.dart';
import '../../core/auth/auth_provider.dart';
import '../../core/api/api_client.dart';
import '../../core/theme/app_theme.dart';
import '../../models/models.dart';
import '../../widgets/app_button.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});
  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  DashboardData? _data;
  bool _loading  = true;
  String? _error;
  String _filter = '180';

  final _currency = NumberFormat.currency(locale: 'en_IN', symbol: '₹', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() { _loading = true; _error = null; });
    try {
      final api  = context.read<ApiClient>();
      final resp = await api.getDashboard(filter: _filter);
      if (resp['status'] == 'success') {
        setState(() { _data = DashboardData.fromJson(resp['data'] as Map<String, dynamic>); });
      } else {
        setState(() => _error = resp['message'] as String? ?? 'Failed to load');
      }
    } catch (e) {
      setState(() => _error = e.toString().replaceAll('Exception: ', ''));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user;

    return Scaffold(
      backgroundColor: AppTheme.background,
      body: RefreshIndicator(
        onRefresh: _load,
        color: AppTheme.primary,
        child: CustomScrollView(
          slivers: [
            // ── App Bar ─────────────────────────────────────
            SliverAppBar(
              expandedHeight: 160,
              pinned: true,
              backgroundColor: AppTheme.primary,
              flexibleSpace: FlexibleSpaceBar(
                background: Container(
                  decoration: const BoxDecoration(
                    gradient: LinearGradient(
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                      colors: [AppTheme.primary, AppTheme.primaryDark],
                    ),
                  ),
                  padding: const EdgeInsets.fromLTRB(20, 60, 20, 16),
                  child: user == null ? const SizedBox() : Row(
                    children: [
                      CircleAvatar(
                        radius: 26,
                        backgroundColor: Colors.white24,
                        backgroundImage: user.photoUrl.isNotEmpty
                            ? NetworkImage(user.photoUrl) : null,
                        child: user.photoUrl.isEmpty
                            ? Text(user.name.isNotEmpty ? user.name[0].toUpperCase() : '?',
                                style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w700))
                            : null,
                      ),
                      const SizedBox(width: 12),
                      Expanded(child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text('Good ${_greeting()}, 👋',
                              style: const TextStyle(color: Colors.white70, fontSize: 13)),
                          const SizedBox(height: 3),
                          Row(
                            children: [
                              Flexible(
                                child: Text(user.name, maxLines: 1, overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w700)),
                              ),
                              const SizedBox(width: 8),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 2),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFF59E0B).withValues(alpha: 0.25),
                                  border: Border.all(color: const Color(0xFFF59E0B)),
                                  borderRadius: BorderRadius.circular(20),
                                ),
                                child: const Row(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Icon(Icons.workspace_premium_rounded, color: Color(0xFFFDE68A), size: 12),
                                    SizedBox(width: 3),
                                    Text('Gold Member', style: TextStyle(color: Color(0xFFFDE68A), fontSize: 10, fontWeight: FontWeight.w700)),
                                  ],
                                ),
                              ),
                            ],
                          ),
                          if (user.chapterName != null)
                            Text(user.chapterName!, maxLines: 1,
                                style: const TextStyle(color: Colors.white60, fontSize: 12)),
                        ],
                      )),
                      IconButton(
                        icon: const Icon(Icons.notifications_outlined, color: Colors.white),
                        onPressed: () => context.push('/notifications'),
                      ),
                    ],
                  ),
                ),
              ),
              actions: [
                IconButton(
                  icon: const Icon(Icons.logout_rounded, color: Colors.white),
                  tooltip: 'Logout',
                  onPressed: _confirmLogout,
                ),
              ],
            ),

            if (_loading)
              const SliverFillRemaining(child: Center(child: CircularProgressIndicator()))
            else if (_error != null)
              SliverFillRemaining(
                child: Center(
                  child: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
                    const Icon(Icons.wifi_off_rounded, size: 54, color: AppTheme.textLight),
                    const SizedBox(height: 12),
                    Text(_error!, textAlign: TextAlign.center,
                        style: const TextStyle(color: AppTheme.textSecondary)),
                    const SizedBox(height: 16),
                    AppButton(label: 'Retry', onPressed: _load, width: 120, icon: Icons.refresh),
                  ]),
                ),
              )
            else ...[
              // Filter tabs
              SliverToBoxAdapter(child: _buildFilterRow()),

              // 4 Hero Quick Actions (EAF Standard)
              SliverToBoxAdapter(child: _buildHeroQuickActions()),

              // Stats grid
              SliverPadding(
                padding: const EdgeInsets.fromLTRB(16, 0, 16, 0),
                sliver: SliverGrid(
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2, mainAxisSpacing: 12, crossAxisSpacing: 12,
                    childAspectRatio: 1.30,
                  ),
                  delegate: SliverChildListDelegate(_buildStatCards()),
                ),
              ),

              // MITB chart
              SliverToBoxAdapter(child: _buildMitbChart()),

              // Weekly performance
              SliverToBoxAdapter(child: _buildWeeklyCard()),

              // Quick links
              SliverToBoxAdapter(child: _buildQuickLinks()),

              const SliverToBoxAdapter(child: SizedBox(height: 100)),
            ],
          ],
        ),
      ),
    );
  }

  String _greeting() {
    final h = DateTime.now().hour;
    if (h < 12) return 'morning';
    if (h < 17) return 'afternoon';
    return 'evening';
  }

  Widget _buildFilterRow() {
    final filters = [('30', '30D'), ('90', '90D'), ('180', '6M'), ('365', '1Y'), ('all', 'All')];
    return SizedBox(
      height: 48,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        itemCount: filters.length,
        separatorBuilder: (_, _) => const SizedBox(width: 8),
        itemBuilder: (_, i) {
          final selected = _filter == filters[i].$1;
          return GestureDetector(
            onTap: () {
              setState(() => _filter = filters[i].$1);
              _load();
            },
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: selected ? AppTheme.primary : Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: selected ? AppTheme.primary : AppTheme.divider),
              ),
              alignment: Alignment.center,
              child: Text(filters[i].$2,
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: selected ? Colors.white : AppTheme.textSecondary,
                  )),
            ),
          );
        },
      ),
    );
  }

  Widget _buildHeroQuickActions() {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Row(
                children: [
                  Icon(Icons.bolt_rounded, color: Color(0xFFD97706), size: 18),
                  SizedBox(width: 6),
                  Text(
                    'Quick Slips',
                    style: TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.w700,
                      color: AppTheme.textPrimary,
                    ),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: const Color(0xFF0F3B68).withValues(alpha: 0.08),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: const Text(
                  'EAF Standard',
                  style: TextStyle(
                    fontSize: 11,
                    fontWeight: FontWeight.w600,
                    color: Color(0xFF0F3B68),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            mainAxisSpacing: 10,
            crossAxisSpacing: 10,
            childAspectRatio: 2.1,
            children: [
              QuickActionCard(
                title: 'Give Referral',
                subtitle: 'Pass lead slip',
                icon: Icons.send_rounded,
                color: const Color(0xFF0F3B68),
                onTap: () => context.go('/connect'),
              ),
              QuickActionCard(
                title: 'Received',
                subtitle: 'Track in-slips',
                icon: Icons.call_received_rounded,
                color: const Color(0xFF10B981),
                onTap: () => context.go('/connect'),
              ),
              QuickActionCard(
                title: '1-to-1 Meeting',
                subtitle: 'Face to face slip',
                icon: Icons.people_alt_rounded,
                color: const Color(0xFF8B5CF6),
                onTap: () => context.go('/f2f'),
              ),
              QuickActionCard(
                title: 'Say Thank You',
                subtitle: 'Closed business',
                icon: Icons.workspace_premium_rounded,
                color: const Color(0xFFD97706),
                onTap: () => context.go('/mitb'),
              ),
            ],
          ),
        ],
      ),
    );
  }

  List<Widget> _buildStatCards() {
    if (_data == null) return [];
    return [
      StatCard(label: 'Referrals Given',  value: '${_data!.referralsGiven}', icon: Icons.send_rounded,        color: AppTheme.primary,  onTap: () => context.go('/connect')),
      StatCard(label: 'Referrals Recv',   value: '${_data!.referralsRecv}',  icon: Icons.call_received_rounded,color: AppTheme.success,  onTap: () => context.go('/connect')),
      StatCard(label: 'MITB Given',       value: _currency.format(_data!.mitbGiven),     icon: Icons.arrow_upward_rounded,  color: AppTheme.warning,  onTap: () => context.go('/mitb')),
      StatCard(label: 'MITB Received',    value: _currency.format(_data!.mitbReceived),  icon: Icons.arrow_downward_rounded,color: AppTheme.info,     onTap: () => context.go('/mitb')),
      StatCard(label: 'F2F Sent',         value: '${_data!.f2fSent}',     icon: Icons.people_outline,         color: const Color(0xFF9B59B6), onTap: () => context.go('/f2f')),
      StatCard(label: 'Visitors',         value: '${_data!.visitors}',    icon: Icons.person_add_outlined,    color: const Color(0xFF16A085), onTap: () => context.go('/visitors')),
    ];
  }

  Widget _buildMitbChart() {
    if (_data == null || _data!.chartDates.isEmpty) return const SizedBox();
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.06), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('MITB Activity', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
          const SizedBox(height: 4),
          const Text('Business given vs received', style: TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
          const SizedBox(height: 16),
          SizedBox(
            height: 180,
            child: LineChart(LineChartData(
              gridData: FlGridData(show: true, drawVerticalLine: false,
                  getDrawingHorizontalLine: (_) => const FlLine(color: AppTheme.divider, strokeWidth: 1)),
              titlesData: FlTitlesData(
                leftTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                bottomTitles: AxisTitles(sideTitles: SideTitles(
                  showTitles: true, reservedSize: 28,
                  interval: (_data!.chartDates.length / 4).ceilToDouble(),
                  getTitlesWidget: (val, meta) {
                    final idx = val.toInt();
                    if (idx < 0 || idx >= _data!.chartDates.length) return const SizedBox();
                    return Padding(padding: const EdgeInsets.only(top: 4),
                        child: Text(_data!.chartDates[idx],
                            style: const TextStyle(fontSize: 9, color: AppTheme.textSecondary)));
                  },
                )),
              ),
              borderData: FlBorderData(show: false),
              lineBarsData: [
                _chartLine(_data!.chartGiven, AppTheme.primary),
                _chartLine(_data!.chartReceived, AppTheme.success),
              ],
            )),
          ),
          const SizedBox(height: 12),
          Row(children: [
            _chartLegend('MITB Given',    AppTheme.primary),
            const SizedBox(width: 16),
            _chartLegend('MITB Received', AppTheme.success),
          ]),
        ],
      ),
    );
  }

  LineChartBarData _chartLine(List<double> data, Color color) => LineChartBarData(
    spots: data.asMap().entries.map((e) => FlSpot(e.key.toDouble(), e.value)).toList(),
    isCurved: true,
    color: color,
    barWidth: 2.5,
    isStrokeCapRound: true,
    dotData: const FlDotData(show: false),
    belowBarData: BarAreaData(show: true, color: color.withValues(alpha: 0.08)),
  );

  Widget _chartLegend(String label, Color color) => Row(children: [
    Container(width: 12, height: 12, decoration: BoxDecoration(color: color, shape: BoxShape.circle)),
    const SizedBox(width: 6),
    Text(label, style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
  ]);

  Widget _buildWeeklyCard() {
    final w = _data?.weekly;
    if (w == null) return const SizedBox();
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.06), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('This Week', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
          const SizedBox(height: 12),
          Row(children: [
            Expanded(child: _weekItem('Ref. Given',     '${w['ref_given'] ?? 0}',     Icons.send_rounded,      AppTheme.primary)),
            Expanded(child: _weekItem('MITB Received',  _currency.format(w['mitb_received'] ?? 0), Icons.currency_rupee, AppTheme.success)),
          ]),
          const SizedBox(height: 8),
          Row(children: [
            Expanded(child: _weekItem('F2F Sent',    '${w['f2f_send'] ?? 0}',   Icons.people_outline,       AppTheme.info)),
            Expanded(child: _weekItem('Visitors',    '${w['visitors'] ?? 0}',   Icons.person_add_outlined,  const Color(0xFF16A085))),
          ]),
        ],
      ),
    );
  }

  Widget _weekItem(String label, String value, IconData icon, Color color) => Padding(
    padding: const EdgeInsets.all(4),
    child: Row(children: [
      Container(padding: const EdgeInsets.all(6), decoration: BoxDecoration(
          color: color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
          child: Icon(icon, color: color, size: 14)),
      const SizedBox(width: 8),
      Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Text(value, style: TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: color)),
        Text(label, style: const TextStyle(fontSize: 10, color: AppTheme.textSecondary)),
      ]),
    ]),
  );

  Widget _buildQuickLinks() {
    final links = [
      (icon: Icons.handshake_outlined,       label: 'Connect',      route: '/connect'),
      (icon: Icons.currency_rupee_outlined,  label: 'MITB',         route: '/mitb'),
      (icon: Icons.people_outline,           label: 'F2F',          route: '/f2f'),
      (icon: Icons.calendar_month_outlined,  label: 'Meetings',     route: '/meetings'),
      (icon: Icons.person_add_outlined,      label: 'Visitors',     route: '/visitors'),
      (icon: Icons.campaign_outlined,        label: 'Broadcasts',   route: '/broadcast'),
      (icon: Icons.group_outlined,           label: 'My Chapter',   route: '/chapter'),
      (icon: Icons.bar_chart_outlined,       label: 'Reports',      route: '/reports'),
    ];
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.06), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Quick Access', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w700)),
          const SizedBox(height: 12),
          GridView.count(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            crossAxisCount: 4,
            mainAxisSpacing: 8,
            crossAxisSpacing: 8,
            childAspectRatio: 0.85,
            children: links.map((l) => GestureDetector(
              onTap: () => context.go(l.route),
              child: Column(children: [
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: AppTheme.primary.withValues(alpha: 0.08),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Icon(l.icon, color: AppTheme.primary, size: 22),
                ),
                const SizedBox(height: 6),
                Text(l.label, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w500, color: AppTheme.textPrimary), textAlign: TextAlign.center),
              ]),
            )).toList(),
          ),
        ],
      ),
    );
  }

  Future<void> _confirmLogout() async {
    final ok = await showDialog<bool>(context: context, builder: (_) => AlertDialog(
      title: const Text('Logout'),
      content: const Text('Are you sure you want to logout?'),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      actions: [
        TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Cancel')),
        ElevatedButton(
          onPressed: () => Navigator.pop(context, true),
          style: ElevatedButton.styleFrom(backgroundColor: AppTheme.error),
          child: const Text('Logout'),
        ),
      ],
    ));
    if (ok == true && mounted) {
      await context.read<AuthProvider>().logout();
      if (mounted) context.go('/login');
    }
  }
}
