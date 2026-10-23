allprojects {
    repositories {
        google()
        mavenCentral()
    }
}

val newBuildDir: Directory =
    rootProject.layout.buildDirectory
        .dir("../../build")
        .get()
rootProject.layout.buildDirectory.value(newBuildDir)

subprojects {
    val newSubprojectBuildDir: Directory = newBuildDir.dir(project.name)
    project.layout.buildDirectory.value(newSubprojectBuildDir)
}
subprojects {
    project.evaluationDependsOn(":app")
}

// Force all Android library plugin subprojects (e.g. fluttertoast) to use
// compileSdk 35 to satisfy AndroidX transitive dependency requirements.
// Uses plugins.withId (fires at plugin-apply time) to avoid the
// "project already evaluated" error caused by evaluationDependsOn above.
subprojects {
    plugins.withId("com.android.library") {
        configure<com.android.build.gradle.LibraryExtension> {
            compileSdk = 36
        }
    }
}

tasks.register<Delete>("clean") {
    delete(rootProject.layout.buildDirectory)
}
