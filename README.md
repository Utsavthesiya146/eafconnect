# EAF Connect Mobile App

Official Mobile Application for **EAF Connect** (`https://eafconnect.com`).

---

## 📱 Application Overview

- **App Name:** EAF Connect
- **Package / Application ID:** `com.eafconnect.app`
- **Current Version:** `1.0.7` (Build `8`)
- **Portal URL:** `https://eafconnect.com/member/dashboard` (Live CodeIgniter 4 Member Portal)
- **Framework:** Flutter (Android & iOS)

---

## 📂 Repository Structure

```text
eaf/
├── .github/
│   └── workflows/
│       ├── android_release.yml   # Automatic GitHub Actions CI/CD for Android APK & AAB
│       └── ios_release.yml       # Automatic GitHub Actions CI/CD for Apple TestFlight & App Store
├── eaf_mobile/                   # Complete Flutter project source
│   ├── android/                  # Android native project & Gradle configs
│   ├── ios/                      # iOS native project & Xcode configs
│   ├── lib/
│   │   ├── core/                 # App configuration & theme
│   │   ├── screens/
│   │   │   ├── portal/           # Live Web Portal screen with native navigation
│   │   │   └── splash/           # Branded Splash Screen with EAF Logo
│   │   ├── app.dart              # Main application widget
│   │   ├── main.dart             # App entry point
│   │   └── router.dart           # App routing
│   └── pubspec.yaml              # Dependencies and version configuration
├── EAF_Connect_v1.0.7.aab        # Latest Google Play Console release bundle
├── EAF_Connect_v1.0.7.apk        # Latest Android device release APK
├── .gitignore                    # Git ignore rules
└── README.md                     # Documentation
```

---

## 🚀 Building & Releasing

### Android (Release Bundle & APK)
Run in PowerShell / Terminal:
```powershell
cd eaf_mobile
flutter build appbundle --release
flutter build apk --release
```

### iOS (TestFlight / App Store)
Pushes to the `main` branch automatically trigger `.github/workflows/ios_release.yml` on macOS runners to compile, code-sign, and upload the build directly to TestFlight and App Store Connect.
