import '../core/config/app_config.dart';

/// Referral record model
class ReferralModel {
  final String referralCode;
  final String fromUser;
  final String toUser;
  final String? fromName;
  final String? fromEmail;
  final String? fromPhoto;
  final String? toName;
  final String? toEmail;
  final String? toPhoto;
  final String? businessType;
  final String? description;
  final double amount;
  final String status;
  final String? chapterName;
  final String? createdAt;
  final String? contactNumber;

  const ReferralModel({
    required this.referralCode,
    required this.fromUser,
    required this.toUser,
    this.fromName,
    this.fromEmail,
    this.fromPhoto,
    this.toName,
    this.toEmail,
    this.toPhoto,
    this.businessType,
    this.description,
    this.amount = 0,
    this.status = '1',
    this.chapterName,
    this.createdAt,
    this.contactNumber,
  });

  factory ReferralModel.fromJson(Map<String, dynamic> j) => ReferralModel(
    referralCode: j['referral_code']?.toString() ?? '',
    fromUser:     j['from_user']?.toString()     ?? '',
    toUser:       j['to_user']?.toString()       ?? '',
    fromName:     j['from_name']?.toString(),
    fromEmail:    j['from_email']?.toString(),
    fromPhoto:    j['from_photo']?.toString(),
    toName:       j['to_name']?.toString(),
    toEmail:      j['to_email']?.toString(),
    toPhoto:      j['to_photo']?.toString(),
    businessType: j['business_type']?.toString() ?? j['business']?.toString(),
    description:  j['description']?.toString(),
    amount:       double.tryParse(j['amount']?.toString() ?? '0') ?? 0,
    status:       j['status']?.toString() ?? '1',
    chapterName:  j['chapter_name']?.toString(),
    createdAt:    j['created_at']?.toString(),
    contactNumber: j['contact_number']?.toString() ?? j['phone']?.toString() ?? j['mobile']?.toString(),
  );
}

/// MITB (Money in the Bank) transaction model
class MitbModel {
  final String mitbCode;
  final String fromUser;
  final String toUser;
  final String? fromName;
  final String? fromPhoto;
  final String? toName;
  final String? toPhoto;
  final double amount;
  final String? businessType;
  final String? description;
  final String status;
  final String? createdAt;

  const MitbModel({
    required this.mitbCode,
    required this.fromUser,
    required this.toUser,
    this.fromName,
    this.fromPhoto,
    this.toName,
    this.toPhoto,
    this.amount = 0,
    this.businessType,
    this.description,
    this.status = '1',
    this.createdAt,
  });

  factory MitbModel.fromJson(Map<String, dynamic> j) => MitbModel(
    mitbCode:     j['mitb_code']?.toString()     ?? '',
    fromUser:     j['from_user']?.toString()     ?? '',
    toUser:       j['to_user']?.toString()       ?? '',
    fromName:     j['from_name']?.toString(),
    fromPhoto:    j['from_photo']?.toString(),
    toName:       j['to_name']?.toString(),
    toPhoto:      j['to_photo']?.toString(),
    amount:       double.tryParse(j['amount']?.toString() ?? '0') ?? 0,
    businessType: j['business_type']?.toString(),
    description:  j['description']?.toString(),
    status:       j['status']?.toString() ?? '1',
    createdAt:    j['created_at']?.toString(),
  );
}

/// F2F (One-to-One) meeting model
class F2fModel {
  final String f2fCode;
  final String fromUser;
  final String toUser;
  final String? fromName;
  final String? fromPhoto;
  final String? toName;
  final String? toPhoto;
  final String? meetingDate;
  final String? agenda;
  final String? location;
  final String? notes;
  final String status;
  final String? createdAt;

  const F2fModel({
    required this.f2fCode,
    required this.fromUser,
    required this.toUser,
    this.fromName, this.fromPhoto, this.toName, this.toPhoto,
    this.meetingDate, this.agenda, this.location, this.notes,
    this.status = '1', this.createdAt,
  });

  factory F2fModel.fromJson(Map<String, dynamic> j) => F2fModel(
    f2fCode:     j['f2f_code']?.toString()  ?? '',
    fromUser:    j['from_user']?.toString() ?? '',
    toUser:      j['to_user']?.toString()   ?? '',
    fromName:    j['from_name']?.toString(),
    fromPhoto:   j['from_photo']?.toString(),
    toName:      j['to_name']?.toString(),
    toPhoto:     j['to_photo']?.toString(),
    meetingDate: j['meeting_date']?.toString(),
    agenda:      j['agenda']?.toString(),
    location:    j['location']?.toString(),
    notes:       j['notes']?.toString(),
    status:      j['status']?.toString() ?? '1',
    createdAt:   j['created_at']?.toString(),
  );
}

/// Meeting / Event model
class MeetingModel {
  final String eventId;
  final String chapterCode;
  final String? chapterName;
  final String? title;
  final String? description;
  final String? date;
  final String? time;
  final String? venue;
  final String status;
  final String? myAttendance;

  const MeetingModel({
    required this.eventId,
    required this.chapterCode,
    this.chapterName, this.title, this.description, this.date,
    this.time, this.venue, this.status = '1', this.myAttendance,
  });

  factory MeetingModel.fromJson(Map<String, dynamic> j) => MeetingModel(
    eventId:      j['event_id']?.toString()    ?? '',
    chapterCode:  j['chapter_code']?.toString() ?? '',
    chapterName:  j['chapter_name']?.toString(),
    title:        j['title']?.toString(),
    description:  j['description']?.toString(),
    date:         j['date']?.toString(),
    time:         j['time']?.toString(),
    venue:        j['venue']?.toString(),
    status:       j['status']?.toString() ?? '1',
    myAttendance: j['my_attendance']?.toString(),
  );
}

/// Visitor model
class VisitorModel {
  final String visitorCode;
  final String userCode;
  final String name;
  final String? email;
  final String? phone;
  final String? business;
  final String? purpose;
  final String status;
  final String? createdAt;

  const VisitorModel({
    required this.visitorCode,
    required this.userCode,
    required this.name,
    this.email, this.phone, this.business, this.purpose,
    this.status = 'Pending', this.createdAt,
  });

  factory VisitorModel.fromJson(Map<String, dynamic> j) => VisitorModel(
    visitorCode: j['visitor_code']?.toString() ?? '',
    userCode:    j['user_code']?.toString()    ?? '',
    name:        j['name']?.toString()         ?? '',
    email:       j['email']?.toString(),
    phone:       j['phone']?.toString(),
    business:    j['business']?.toString(),
    purpose:     j['purpose']?.toString(),
    status:      j['status']?.toString() ?? 'Pending',
    createdAt:   j['created_at']?.toString(),
  );
}

/// Notification model
class NotificationModel {
  final String notificationId;
  final String userCode;
  final String? chapterCode;
  final String? chapterName;
  final String? title;
  final String? message;
  final String status;
  final String? createdAt;

  const NotificationModel({
    required this.notificationId,
    required this.userCode,
    this.chapterCode, this.chapterName, this.title, this.message,
    this.status = '1', this.createdAt,
  });

  bool get isUnread => status == '1';

  factory NotificationModel.fromJson(Map<String, dynamic> j) => NotificationModel(
    notificationId: j['notification_id']?.toString() ?? '',
    userCode:       j['user_code']?.toString()       ?? '',
    chapterCode:    j['chapter_code']?.toString(),
    chapterName:    j['chapter_name']?.toString(),
    title:          j['title']?.toString(),
    message:        j['message']?.toString(),
    status:         j['status']?.toString() ?? '1',
    createdAt:      j['created_at']?.toString(),
  );
}

/// Broadcast model
class BroadcastModel {
  final String broadcastId;
  final String chapterCode;
  final String? chapterName;
  final String? title;
  final String? message;
  final String status;
  final String? createdAt;

  const BroadcastModel({
    required this.broadcastId,
    required this.chapterCode,
    this.chapterName, this.title, this.message,
    this.status = '1', this.createdAt,
  });

  factory BroadcastModel.fromJson(Map<String, dynamic> j) => BroadcastModel(
    broadcastId: j['broadcast_id']?.toString() ?? '',
    chapterCode: j['chapter_code']?.toString() ?? '',
    chapterName: j['chapter_name']?.toString(),
    title:       j['title']?.toString(),
    message:     j['message']?.toString(),
    status:      j['status']?.toString() ?? '1',
    createdAt:   j['created_at']?.toString(),
  );
}

/// Chapter member model (lightweight, for directory)
class ChapterMemberModel {
  final String userCode;
  final String name;
  final String? email;
  final String? phone;
  final String? photoUrl;
  final String? businessName;
  final String? industry;
  final String? designation;
  final String? memberRole;

  const ChapterMemberModel({
    required this.userCode,
    required this.name,
    this.email, this.phone, this.photoUrl, this.businessName,
    this.industry, this.designation, this.memberRole,
  });

  factory ChapterMemberModel.fromJson(Map<String, dynamic> j) => ChapterMemberModel(
    userCode:    j['user_code']?.toString()    ?? '',
    name:        j['name']?.toString()         ?? '',
    email:       j['email']?.toString(),
    phone:       j['phone']?.toString(),
    photoUrl:    j['photo_url']?.toString() ?? AppConfig.profilePhotoUrl(j['profile_photo']?.toString()),
    businessName: j['business_name']?.toString(),
    industry:    j['industry']?.toString(),
    designation: j['designation']?.toString(),
    memberRole:  j['member_role']?.toString(),
  );
}

/// Dashboard data model
class DashboardData {
  final int meetingsCount;
  final int referralsGiven;
  final int referralsRecv;
  final int visitors;
  final int f2fSent;
  final int f2fReceived;
  final int notifications;
  final double mitbGiven;
  final double mitbReceived;
  final int inducationCount;
  final int profileStrength;
  final List<String> chartDates;
  final List<double> chartGiven;
  final List<double> chartReceived;
  final Map<String, dynamic>? weekly;
  final Map<String, dynamic>? chapter;

  const DashboardData({
    this.meetingsCount = 0,
    this.referralsGiven = 0,
    this.referralsRecv = 0,
    this.visitors = 0,
    this.f2fSent = 0,
    this.f2fReceived = 0,
    this.notifications = 0,
    this.mitbGiven = 0,
    this.mitbReceived = 0,
    this.inducationCount = 0,
    this.profileStrength = 0,
    this.chartDates = const [],
    this.chartGiven = const [],
    this.chartReceived = const [],
    this.weekly,
    this.chapter,
  });

  factory DashboardData.fromJson(Map<String, dynamic> j) {
    final chart = j['chart'] as Map<String, dynamic>? ?? {};
    return DashboardData(
      meetingsCount:   (j['meetings_count']   as num? ?? 0).toInt(),
      referralsGiven:  (j['referrals_given']  as num? ?? 0).toInt(),
      referralsRecv:   (j['referrals_recv']   as num? ?? 0).toInt(),
      visitors:        (j['visitors']         as num? ?? 0).toInt(),
      f2fSent:         (j['f2f_sent']         as num? ?? 0).toInt(),
      f2fReceived:     (j['f2f_received']     as num? ?? 0).toInt(),
      notifications:   (j['notifications']    as num? ?? 0).toInt(),
      mitbGiven:       (j['mitb_given']       as num? ?? 0).toDouble(),
      mitbReceived:    (j['mitb_received']    as num? ?? 0).toDouble(),
      inducationCount: (j['induction_count']  as num? ?? 0).toInt(),
      profileStrength: (j['profile_strength'] as num? ?? 0).toInt(),
      chartDates:      (chart['dates']    as List<dynamic>? ?? []).map((e) => e.toString()).toList(),
      chartGiven:      (chart['given']    as List<dynamic>? ?? []).map((e) => (e as num).toDouble()).toList(),
      chartReceived:   (chart['received'] as List<dynamic>? ?? []).map((e) => (e as num).toDouble()).toList(),
      weekly:          j['weekly']  as Map<String, dynamic>?,
      chapter:         j['chapter'] as Map<String, dynamic>?,
    );
  }
}
