/// Built-in Demo Data Provider for Appetize, Screen Recordings, and Apple Review.
/// Guarantees that the app functions 100% reliably even when the backend
/// server is undergoing maintenance, restricted, or offline.
class DemoMockData {
  DemoMockData._();

  static Map<String, dynamic> loginResponse() => {
    'status': 'success',
    'message': 'Login successful.',
    'token': 'demo_reviewer_session_token_eaf_998877',
    'user': {
      'user_id': '5',
      'user_code': '5',
      'name': 'Harri',
      'last_name': 'Kumar',
      'email': 'harri@jobhunterr.com',
      'phone': '9341873532',
      'role': 'member',
      'member_role': 'President',
      'member_type': 'Gold Member',
      'status': '1',
      'profile_photo': 'empty.png',
      'gender': 'Male',
      'dob': '1987-09-10',
      'address': 'Basaveshwara Nagar',
      'city': 'Bangalore',
      'state': 'Karnataka',
      'country': 'India',
      'pincode': '560072',
      'designation': 'CEO',
      'exp_years': '10',
      'skills': 'Website Development, Digital Marketing, SEO',
      'bio': 'Founder of Bangalore Jobhunterr India Pvt Ltd. Driving business growth and entrepreneur networking.',
      'business_name': 'Hosting Baba',
      'industry': 'Software & Web Development',
      'description': 'End-to-end digital marketing and web application services.',
      'gst_number': '29AAKCB0612Q1ZC',
      'website': 'https://www.webhostingbaba.com',
      'chapter_code': 'CH001',
      'chapter_name': 'EAF Elite Chapter',
      'chapter_role': 'President',
      'verified': true,
    }
  };

  static Map<String, dynamic> dashboardResponse() => {
    'status': 'success',
    'data': {
      'meetings_count': 26,
      'referrals_given': 19,
      'referrals_recv': 24,
      'visitors': 12,
      'f2f_sent': 15,
      'f2f_received': 14,
      'notifications': 2,
      'mitb_given': 950000.0,
      'mitb_received': 1680000.0,
      'inducation_count': 6,
      'profile_strength': 100,
      'chart': {
        'dates': ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        'given': [150000.0, 280000.0, 210000.0, 310000.0],
        'received': [250000.0, 390000.0, 480000.0, 560000.0],
      },
      'weekly': {
        'ref_given': 4,
        'mitb_received': 85000.0,
        'f2f_send': 3,
        'visitors': 2,
      },
      'chapter': {
        'chapter_name': 'EAF Elite Chapter',
        'members_count': 52,
      }
    }
  };

  static Map<String, dynamic> profileResponse() => {
    'status': 'success',
    'data': {
      'user': loginResponse()['user'],
    }
  };

  static Map<String, dynamic> connectsResponse() => {
    'status': 'success',
    'data': {
      'given': [
        {
          'referral_code': 'REF001',
          'from_user': '5',
          'to_user': '8',
          'from_name': 'Harri Kumar',
          'to_name': 'Vinod K',
          'business_type': 'Turnkey Interior Design',
          'description': 'Client looking for commercial office interior fitouts',
          'amount': '150000',
          'status': '1',
          'created_at': '2026-10-25 10:30:00',
          'contact_number': '9900932462'
        },
        {
          'referral_code': 'REF002',
          'from_user': '5',
          'to_user': '75',
          'from_name': 'Harri Kumar',
          'to_name': 'Anand B C',
          'business_type': 'GST & Tax Advisory',
          'description': 'Corporate tax audit requirement for startup',
          'amount': '45000',
          'status': '1',
          'created_at': '2026-10-22 14:15:00',
          'contact_number': '9741914188'
        }
      ],
      'received': [
        {
          'referral_code': 'REF003',
          'from_user': '20',
          'to_user': '5',
          'from_name': 'Swaroop S',
          'to_name': 'Harri Kumar',
          'business_type': 'E-Commerce Website',
          'description': 'Client requires custom web portal development',
          'amount': '250000',
          'status': '1',
          'created_at': '2026-10-26 11:00:00',
          'contact_number': '9019923915'
        }
      ]
    }
  };

  static List<dynamic> chapterMembersList() => [
    {'user_code': '8', 'name': 'Vinod K', 'business_name': 'Vibrant Design Studio'},
    {'user_code': '20', 'name': 'Swaroop S', 'business_name': 'Swaroop Suri & Associates'},
    {'user_code': '75', 'name': 'Anand B C', 'business_name': 'AP Associates'},
    {'user_code': '30', 'name': 'Daksha Kumar TL', 'business_name': 'Printastic'},
    {'user_code': '92', 'name': 'Ajay R', 'business_name': 'Sanjeevni Finnest'},
  ];

  static Map<String, dynamic> mitbResponse() => {
    'status': 'success',
    'data': {
      'given': [
        {
          'mitb_code': 'MB001',
          'from_user': '5',
          'to_user': '8',
          'from_name': 'Harri Kumar',
          'to_name': 'Vinod K',
          'amount': '350000',
          'business_type': 'Office Interiors',
          'created_at': '2026-10-20'
        }
      ],
      'received': [
        {
          'mitb_code': 'MB002',
          'from_user': '20',
          'to_user': '5',
          'from_name': 'Swaroop S',
          'to_name': 'Harri Kumar',
          'amount': '180000',
          'business_type': 'Software Portal',
          'created_at': '2026-10-24'
        }
      ]
    }
  };

  static Map<String, dynamic> meetingsResponse() => {
    'status': 'success',
    'data': [
      {
        'event_id': 'EV001',
        'chapter_code': 'CH001',
        'chapter_name': 'EAF Elite Chapter',
        'title': 'Weekly Chapter Business Meeting',
        'description': 'Referral exchange, member presentations, and visitor day',
        'date': '2026-10-31',
        'time': '07:30 AM',
        'venue': 'The Chancery Pavilion, Residency Road, Bangalore',
        'status': '1',
        'my_attendance': 'Present'
      },
      {
        'event_id': 'EV002',
        'chapter_code': 'CH001',
        'chapter_name': 'EAF Elite Chapter',
        'title': 'EAF Business Networking Conclave',
        'description': 'Summit with 100+ entrepreneurs and investors',
        'date': '2026-11-14',
        'time': '09:00 AM',
        'venue': 'ITC Gardenia, Bangalore',
        'status': '1',
        'my_attendance': 'Registered'
      }
    ]
  };

  static Map<String, dynamic> chapterResponse() => {
    'status': 'success',
    'data': {
      'chapter': {
        'chapter_name': 'EAF Elite Chapter',
        'meeting_day': 'Every Saturday',
        'meeting_time': '07:30 AM',
        'venue': 'The Chancery Pavilion, Bangalore'
      },
      'members': [
        {
          'user_code': '5',
          'name': 'Harri Kumar',
          'business_name': 'Hosting Baba',
          'industry': 'Software & Web Development',
          'member_role': 'President',
          'phone': '9341873532',
          'email': 'harri@jobhunterr.com'
        },
        {
          'user_code': '8',
          'name': 'Vinod K',
          'business_name': 'Vibrant Design Studio',
          'industry': 'Interior Design',
          'member_role': 'Vice President',
          'phone': '9900932462'
        },
        {
          'user_code': '20',
          'name': 'Swaroop S',
          'business_name': 'Swaroop Suri & Associates',
          'industry': 'Corporate Legal & Governance',
          'member_role': 'Secretary',
          'phone': '9019923915'
        },
        {
          'user_code': '75',
          'name': 'Anand B C',
          'business_name': 'AP Associates',
          'industry': 'Tax & Audit Advisory',
          'member_role': 'Treasurer',
          'phone': '9741914188'
        }
      ]
    }
  };

  static Map<String, dynamic> notificationsResponse() => {
    'status': 'success',
    'data': {
      'notifications': [
        {
          'notification_id': 'N001',
          'user_code': '5',
          'title': 'New Business Referral',
          'message': 'Swaroop S passed a new referral for Web Portal Development',
          'status': '1',
          'created_at': '2 hours ago',
        },
        {
          'notification_id': 'N002',
          'user_code': '5',
          'title': 'Weekly Chapter Meeting',
          'message': 'Weekly Chapter Meeting scheduled for Saturday 7:30 AM',
          'status': '1',
          'created_at': '1 day ago',
        },
      ],
      'unread_count': 2,
      'total': 2,
    },
  };

  static Map<String, dynamic> successResponse([String msg = 'Action completed successfully']) => {
    'status': 'success',
    'message': msg,
  };
}
