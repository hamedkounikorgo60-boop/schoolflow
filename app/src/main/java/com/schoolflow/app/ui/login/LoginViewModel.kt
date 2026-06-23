package com.schoolflow.app.ui.login

import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.schoolflow.app.data.local.TokenManager
import com.schoolflow.app.data.repository.ApiResult
import com.schoolflow.app.data.repository.AuthRepository
import kotlinx.coroutines.launch

data class LoginUiState(
    val email: String = "",
    val password: String = "",
    val isLoading: Boolean = false,
    val errorMessage: String? = null,
    val isLoggedIn: Boolean = false,
    val userRole: String = "",
    val eleveId: Int? = null
)

class LoginViewModel(
    private val tokenManager: TokenManager
) : ViewModel() {

    private val repository = AuthRepository()

    var uiState by mutableStateOf(LoginUiState())
        private set

    fun onEmailChange(value: String) {
        uiState = uiState.copy(email = value, errorMessage = null)
    }

    fun onPasswordChange(value: String) {
        uiState = uiState.copy(password = value, errorMessage = null)
    }

    fun login() {
        if (uiState.email.isBlank() || uiState.password.isBlank()) {
            uiState = uiState.copy(errorMessage = "Veuillez remplir tous les champs.")
            return
        }

        uiState = uiState.copy(isLoading = true, errorMessage = null)

        viewModelScope.launch {
            val result = repository.login(uiState.email, uiState.password)
            when (result) {
                is ApiResult.Success -> {
                    val eleveId = result.data.user.eleve_id

                    tokenManager.saveToken(
                        token = "Bearer ${result.data.token}",
                        userName = result.data.user.name,
                        role = result.data.user.role
                    )

                    if (eleveId != null) {
                        tokenManager.saveEleveId(eleveId)
                    }

                    if (result.data.user.role == "parent" && eleveId == null) {
                        uiState = uiState.copy(
                            isLoading = false,
                            errorMessage = "Aucun élève n'est associé à ce compte parent."
                        )
                        return@launch
                    }

                    uiState = uiState.copy(
                        isLoading = false,
                        isLoggedIn = true,
                        userRole = result.data.user.role,
                        eleveId = eleveId
                    )
                }
                is ApiResult.Error -> {
                    uiState = uiState.copy(
                        isLoading = false,
                        errorMessage = result.message
                    )
                }
            }
        }
    }
}
