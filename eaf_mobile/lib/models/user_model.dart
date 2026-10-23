import '../core/config/app_config.dart';

class UserModel {
  final String userId;
  final String userCode;
  final String name;
  final String lastName;
  final String email;
  final String phone;
  final String role;
  final String memberRole;
  final String memberType;
  final String status;
  final String profilePhoto;
  final String? gender;
  final String? dob;
  final String? address;
  final String? city;
  final String? state;
  final String? country;
  final String? pincode;
  final String? designation;
  final String? expYears;
  final String? skills;
  final String? bio;
  final String? businessName;
  final String? industry;
  final String? description;
  final String? gstNumber;
  final String? website;
  final String? linkedin;
  final String? facebook;
  final String? instagram;
  final String? twitter;
  final String? youtube;
  final String? businessLogo;
  final String? businessCard;
  final String? chapterCode;
  final String? chapterRole;
  final String? chapterName;
  final bool verified;

  const UserModel({
    required this.userId,
    required this.userCode,
    required this.name,
    required this.lastName,
    required this.email,
    required this.phone,
    required this.role,
    required this.memberRole,
    required this.memberType,
    required this.status,
    required this.profilePhoto,
    this.gender,
    this.dob,
    this.address,
    this.city,
    this.state,
    this.country,
    this.pincode,
    this.designation,
    this.expYears,
    this.skills,
    this.bio,
    this.businessName,
    this.industry,
    this.description,
    this.gstNumber,
    this.website,
    this.linkedin,
    this.facebook,
    this.instagram,
    this.twitter,
    this.youtube,
    this.businessLogo,
    this.businessCard,
    this.chapterCode,
    this.chapterRole,
    this.chapterName,
    this.verified = false,
  });

  String get fullName    => [name, lastName].where((s) => s.isNotEmpty).join(' ');
  String get displayName => fullName.isNotEmpty ? fullName : email;
  String get photoUrl    => AppConfig.profilePhotoUrl(profilePhoto);
  bool   get isAdmin     => role == 'admin';
  bool   get isMember    => role == 'member';

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      userId:      json['user_id']?.toString()   ?? '',
      userCode:    json['user_code']?.toString() ?? '',
      name:        json['name']?.toString()      ?? '',
      lastName:    json['last_name']?.toString() ?? '',
      email:       json['email']?.toString()     ?? '',
      phone:       json['phone']?.toString()     ?? '',
      role:        json['role']?.toString()      ?? 'member',
      memberRole:  json['member_role']?.toString() ?? '',
      memberType:  json['member_type']?.toString() ?? '',
      status:      json['status']?.toString()    ?? '1',
      profilePhoto: json['profile_photo']?.toString() ?? 'empty.png',
      gender:      json['gender']?.toString(),
      dob:         json['dob']?.toString(),
      address:     json['address']?.toString(),
      city:        json['city']?.toString(),
      state:       json['state']?.toString(),
      country:     json['country']?.toString(),
      pincode:     json['pincode']?.toString(),
      designation: json['designation']?.toString(),
      expYears:    json['exp_years']?.toString(),
      skills:      json['skills']?.toString(),
      bio:         json['bio']?.toString(),
      businessName: json['business_name']?.toString(),
      industry:    json['industry']?.toString(),
      description: json['description']?.toString(),
      gstNumber:   json['gst_number']?.toString(),
      website:     json['website']?.toString(),
      linkedin:    json['linkedin']?.toString(),
      facebook:    json['facebook']?.toString(),
      instagram:   json['instagram']?.toString(),
      twitter:     json['twitter']?.toString(),
      youtube:     json['youtube']?.toString(),
      businessLogo: json['business_logo']?.toString(),
      businessCard: json['business_card']?.toString(),
      chapterCode: json['chapter_code']?.toString(),
      chapterRole: json['chapter_role']?.toString(),
      chapterName: json['chapter_name']?.toString(),
      verified:    json['verified']?.toString() == '1',
    );
  }

  Map<String, dynamic> toJson() => {
    'user_id': userId, 'user_code': userCode, 'name': name,
    'last_name': lastName, 'email': email, 'phone': phone,
    'role': role, 'member_role': memberRole, 'member_type': memberType,
    'status': status, 'profile_photo': profilePhoto, 'gender': gender,
    'dob': dob, 'address': address, 'city': city, 'state': state,
    'country': country, 'pincode': pincode, 'designation': designation,
    'exp_years': expYears, 'skills': skills, 'bio': bio,
    'business_name': businessName, 'industry': industry,
    'description': description, 'gst_number': gstNumber,
    'website': website, 'linkedin': linkedin, 'facebook': facebook,
    'instagram': instagram, 'twitter': twitter, 'youtube': youtube,
    'business_logo': businessLogo, 'business_card': businessCard,
    'chapter_code': chapterCode, 'chapter_role': chapterRole,
    'chapter_name': chapterName, 'verified': verified ? '1' : '0',
  };

  UserModel copyWith({
    String? name, String? lastName, String? email, String? phone,
    String? gender, String? dob, String? address, String? city,
    String? state, String? country, String? pincode, String? designation,
    String? expYears, String? skills, String? bio, String? businessName,
    String? industry, String? description, String? profilePhoto,
    String? businessLogo, String? businessCard, String? chapterCode,
    String? chapterName,
  }) {
    return UserModel(
      userId: userId, userCode: userCode, role: role,
      memberRole: memberRole, memberType: memberType, status: status,
      verified: verified,
      name:        name        ?? this.name,
      lastName:    lastName    ?? this.lastName,
      email:       email       ?? this.email,
      phone:       phone       ?? this.phone,
      gender:      gender      ?? this.gender,
      dob:         dob         ?? this.dob,
      address:     address     ?? this.address,
      city:        city        ?? this.city,
      state:       state       ?? this.state,
      country:     country     ?? this.country,
      pincode:     pincode     ?? this.pincode,
      designation: designation ?? this.designation,
      expYears:    expYears    ?? this.expYears,
      skills:      skills      ?? this.skills,
      bio:         bio         ?? this.bio,
      businessName:businessName?? this.businessName,
      industry:    industry    ?? this.industry,
      description: description ?? this.description,
      profilePhoto:profilePhoto?? this.profilePhoto,
      businessLogo:businessLogo?? this.businessLogo,
      businessCard:businessCard?? this.businessCard,
      chapterCode: chapterCode ?? this.chapterCode,
      chapterName: chapterName ?? this.chapterName,
      chapterRole: chapterRole, gstNumber: gstNumber,
      website: website, linkedin: linkedin, facebook: facebook,
      instagram: instagram, twitter: twitter, youtube: youtube,
    );
  }
}
