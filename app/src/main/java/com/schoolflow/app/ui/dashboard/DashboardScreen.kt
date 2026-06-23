package com.schoolflow.app.ui.dashboard

import androidx.compose.foundation.layout.*
import androidx.compose.foundation.background
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.EmojiEvents
import androidx.compose.material.icons.filled.EventBusy
import androidx.compose.material.icons.filled.Logout
import androidx.compose.material.icons.filled.MenuBook
import androidx.compose.material.icons.filled.Payments
import androidx.compose.material.icons.filled.Refresh
import androidx.compose.material.icons.filled.Settings
import androidx.compose.material.icons.filled.School
import androidx.compose.material3.*
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.schoolflow.app.data.local.TokenManager
import kotlinx.coroutines.launch

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun DashboardScreen(
    tokenManager: TokenManager,
    onLogout: () -> Unit,
    onNavigateToNotes: () -> Unit = {},
    onNavigateToPaiements: () -> Unit = {},
    onNavigateToAbsences: () -> Unit = {},
    onNavigateToChangePassword: () -> Unit = {}
) {
    val viewModel: DashboardViewModel = viewModel(
        factory = DashboardViewModelFactory(tokenManager)
    )
    val state = viewModel.uiState
    val scope = rememberCoroutineScope()

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("SchoolFlow") },
                actions = {
                    IconButton(onClick = onNavigateToChangePassword) {
                        Icon(Icons.Default.Settings, contentDescription = "Paramètres")
                    }
                    IconButton(onClick = { viewModel.loadDashboard() }) {
                        Icon(Icons.Default.Refresh, contentDescription = "Actualiser")
                    }
                    IconButton(onClick = {
                        scope.launch {
                            viewModel.logout()
                            onLogout()
                        }
                    }) {
                        Icon(Icons.Default.Logout, contentDescription = "Déconnexion")
                    }
                }
            )
        }
    ) { padding ->

        when {
            state.isLoading -> {
                Box(
                    modifier = Modifier.fillMaxSize().padding(padding),
                    contentAlignment = Alignment.Center
                ) {
                    CircularProgressIndicator()
                }
            }

            state.errorMessage != null -> {
                Box(
                    modifier = Modifier.fillMaxSize().padding(padding).padding(24.dp),
                    contentAlignment = Alignment.Center
                ) {
                    Column(horizontalAlignment = Alignment.CenterHorizontally) {
                        Text(
                            text = state.errorMessage,
                            color = MaterialTheme.colorScheme.error,
                            fontSize = 14.sp
                        )
                        Spacer(modifier = Modifier.height(16.dp))
                        Button(onClick = { viewModel.loadDashboard() }) {
                            Text("Réessayer")
                        }
                    }
                }
            }

            state.data != null -> {
                val data = state.data
                LazyColumn(
                    modifier = Modifier
                        .fillMaxSize()
                        .padding(padding)
                        .padding(16.dp),
                    verticalArrangement = Arrangement.spacedBy(16.dp)
                ) {
                    item {
                        Card(
                            modifier = Modifier.fillMaxWidth(),
                            colors = CardDefaults.cardColors(
                                containerColor = MaterialTheme.colorScheme.primaryContainer
                            )
                        ) {
                            Row(
                                modifier = Modifier.padding(16.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Box(
                                    modifier = Modifier
                                        .size(56.dp)
                                        .clip(CircleShape)
                                        .background(MaterialTheme.colorScheme.primary),
                                    contentAlignment = Alignment.Center
                                ) {
                                    Icon(
                                        imageVector = Icons.Default.School,
                                        contentDescription = null,
                                        tint = MaterialTheme.colorScheme.onPrimary
                                    )
                                }
                                Spacer(modifier = Modifier.width(16.dp))
                                Column {
                                    Text(
                                        text = "${data.eleve.nom} ${data.eleve.prenoms}",
                                        fontWeight = FontWeight.Bold,
                                        fontSize = 17.sp
                                    )
                                    Text(
                                        text = "${data.eleve.classe} · ${data.eleve.matricule}",
                                        fontSize = 13.sp,
                                        color = MaterialTheme.colorScheme.onSurfaceVariant
                                    )
                                }
                            }
                        }
                    }

                    item {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.spacedBy(12.dp)
                        ) {
                            StatCard(
                                modifier = Modifier.weight(1f),
                                label = "Moyenne générale",
                                value = data.moyenne_generale?.let { "$it/20" } ?: "N/A",
                                icon = Icons.Default.MenuBook
                            )
                            StatCard(
                                modifier = Modifier.weight(1f),
                                label = "Rang",
                                value = "${data.rang}/${data.total_eleves}",
                                icon = Icons.Default.EmojiEvents
                            )
                        }
                    }

                    item {
                        Card(
                            modifier = Modifier.fillMaxWidth().clip(RoundedCornerShape(12.dp)),
                            onClick = onNavigateToNotes
                        ) {
                            Row(
                                modifier = Modifier.padding(16.dp).fillMaxWidth(),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(Icons.Default.MenuBook, contentDescription = null)
                                Spacer(modifier = Modifier.width(12.dp))
                                Text("Voir toutes les notes", fontWeight = FontWeight.Medium)
                            }
                        }
                    }

                    item {
                        Card(
                            modifier = Modifier.fillMaxWidth().clip(RoundedCornerShape(12.dp)),
                            onClick = onNavigateToPaiements
                        ) {
                            Row(
                                modifier = Modifier.padding(16.dp).fillMaxWidth(),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(Icons.Default.Payments, contentDescription = null)
                                Spacer(modifier = Modifier.width(12.dp))
                                Text("Voir les paiements", fontWeight = FontWeight.Medium)
                            }
                        }
                    }

                    item {
                        Card(
                            modifier = Modifier.fillMaxWidth().clip(RoundedCornerShape(12.dp)),
                            onClick = onNavigateToAbsences
                        ) {
                            Row(
                                modifier = Modifier.padding(16.dp).fillMaxWidth(),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(Icons.Default.EventBusy, contentDescription = null)
                                Spacer(modifier = Modifier.width(12.dp))
                                Text("Voir les absences", fontWeight = FontWeight.Medium)
                            }
                        }
                    }

                    item {
                        Text(
                            text = "Dernières notes",
                            fontWeight = FontWeight.Bold,
                            fontSize = 16.sp
                        )
                    }

                    items(data.dernieres_notes) { note ->
                        Card(modifier = Modifier.fillMaxWidth()) {
                            Row(
                                modifier = Modifier.fillMaxWidth().padding(14.dp),
                                horizontalArrangement = Arrangement.SpaceBetween,
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Text(note.matiere, fontSize = 14.sp)
                                Text(
                                    "${note.note}/20",
                                    fontWeight = FontWeight.Bold,
                                    color = when {
                                        note.note >= 14 -> MaterialTheme.colorScheme.primary
                                        note.note >= 10 -> MaterialTheme.colorScheme.tertiary
                                        else -> MaterialTheme.colorScheme.error
                                    }
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun StatCard(
    modifier: Modifier = Modifier,
    label: String,
    value: String,
    icon: androidx.compose.ui.graphics.vector.ImageVector
) {
    Card(modifier = modifier) {
        Column(
            modifier = Modifier.padding(16.dp),
            horizontalAlignment = Alignment.Start
        ) {
            Icon(icon, contentDescription = null, tint = MaterialTheme.colorScheme.primary)
            Spacer(modifier = Modifier.height(8.dp))
            Text(text = value, fontWeight = FontWeight.Bold, fontSize = 20.sp)
            Text(text = label, fontSize = 12.sp, color = MaterialTheme.colorScheme.onSurfaceVariant)
        }
    }
}
