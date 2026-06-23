package com.schoolflow.app

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import com.schoolflow.app.data.local.TokenManager
import com.schoolflow.app.ui.absences.AbsencesScreen
import com.schoolflow.app.ui.dashboard.DashboardScreen
import com.schoolflow.app.ui.login.LoginScreen
import com.schoolflow.app.ui.notes.NotesScreen
import com.schoolflow.app.ui.paiements.PaiementsScreen
import com.schoolflow.app.ui.password.ChangePasswordScreen
import com.schoolflow.app.ui.theme.SchoolFlowTheme

class MainActivity : ComponentActivity() {

    private lateinit var tokenManager: TokenManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()

        tokenManager = TokenManager(applicationContext)

        setContent {
            SchoolFlowTheme {
                Surface(
                    modifier = Modifier.fillMaxSize(),
                    color = MaterialTheme.colorScheme.background
                ) {
                    SchoolFlowApp(tokenManager)
                }
            }
        }
    }
}

private enum class Screen {
    LOGIN, DASHBOARD, CHANGE_PASSWORD, NOTES, PAIEMENTS, ABSENCES
}

@Composable
fun SchoolFlowApp(tokenManager: TokenManager) {
    var currentScreen by remember { mutableStateOf(Screen.LOGIN) }

    when (currentScreen) {
        Screen.LOGIN -> {
            LoginScreen(
                tokenManager = tokenManager,
                onLoginSuccess = { role ->
                    currentScreen = Screen.DASHBOARD
                }
            )
        }
        Screen.DASHBOARD -> {
            DashboardScreen(
                tokenManager = tokenManager,
                onLogout = {
                    currentScreen = Screen.LOGIN
                },
                onNavigateToChangePassword = {
                    currentScreen = Screen.CHANGE_PASSWORD
                },
                onNavigateToNotes = {
                    currentScreen = Screen.NOTES
                },
                onNavigateToPaiements = {
                    currentScreen = Screen.PAIEMENTS
                },
                onNavigateToAbsences = {
                    currentScreen = Screen.ABSENCES
                }
            )
        }
        Screen.CHANGE_PASSWORD -> {
            ChangePasswordScreen(
                tokenManager = tokenManager,
                onBack = { currentScreen = Screen.DASHBOARD }
            )
        }
        Screen.NOTES -> {
            NotesScreen(
                tokenManager = tokenManager,
                onBack = { currentScreen = Screen.DASHBOARD }
            )
        }
        Screen.PAIEMENTS -> {
            PaiementsScreen(
                tokenManager = tokenManager,
                onBack = { currentScreen = Screen.DASHBOARD }
            )
        }
        Screen.ABSENCES -> {
            AbsencesScreen(
                tokenManager = tokenManager,
                onBack = { currentScreen = Screen.DASHBOARD }
            )
        }
    }
}
