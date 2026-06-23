package com.schoolflow.app.data.repository

import com.schoolflow.app.data.api.RetrofitClient
import com.schoolflow.app.data.model.ChangePasswordRequest
import com.schoolflow.app.data.model.LoginRequest
import com.schoolflow.app.data.model.LoginResponse
import com.schoolflow.app.data.model.MessageResponse

sealed class ApiResult<out T> {
    data class Success<T>(val data: T) : ApiResult<T>()
    data class Error(val message: String) : ApiResult<Nothing>()
}

class AuthRepository {

    suspend fun login(email: String, password: String): ApiResult<LoginResponse> {
        return try {
            val response = RetrofitClient.apiService.login(LoginRequest(email, password))
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                val msg = when (response.code()) {
                    401 -> "Email ou mot de passe incorrect."
                    else -> "Erreur serveur (${response.code()})."
                }
                ApiResult.Error(msg)
            }
        } catch (e: Exception) {
            ApiResult.Error("Impossible de se connecter au serveur. Vérifiez votre connexion.")
        }
    }

    suspend fun changePassword(
        token: String,
        currentPassword: String,
        newPassword: String
    ): ApiResult<MessageResponse> {
        return try {
            val response = RetrofitClient.apiService.changePassword(
                token,
                ChangePasswordRequest(
                    current_password = currentPassword,
                    new_password = newPassword,
                    new_password_confirmation = newPassword
                )
            )
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                val msg = when (response.code()) {
                    422 -> "Mot de passe actuel incorrect."
                    else -> "Erreur serveur (${response.code()})."
                }
                ApiResult.Error(msg)
            }
        } catch (e: Exception) {
            ApiResult.Error("Impossible de se connecter au serveur.")
        }
    }
}
