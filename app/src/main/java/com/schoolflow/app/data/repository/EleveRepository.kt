package com.schoolflow.app.data.repository

import com.schoolflow.app.data.api.RetrofitClient
import com.schoolflow.app.data.model.AbsencesResponse
import com.schoolflow.app.data.model.DashboardResponse
import com.schoolflow.app.data.model.NotesResponse
import com.schoolflow.app.data.model.PaiementsResponse

class EleveRepository {

    suspend fun getDashboard(token: String, eleveId: Int, trimestre: String): ApiResult<DashboardResponse> {
        return try {
            val response = RetrofitClient.apiService.getDashboard(token, eleveId, trimestre)
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                ApiResult.Error("Erreur lors du chargement (${response.code()}).")
            }
        } catch (e: Exception) {
            ApiResult.Error("Connexion impossible : ${e.message}")
        }
    }

    suspend fun getNotes(token: String, eleveId: Int, trimestre: String): ApiResult<NotesResponse> {
        return try {
            val response = RetrofitClient.apiService.getNotes(token, eleveId, trimestre)
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                ApiResult.Error("Erreur lors du chargement des notes (${response.code()}).")
            }
        } catch (e: Exception) {
            ApiResult.Error("Connexion impossible : ${e.message}")
        }
    }

    suspend fun getPaiements(token: String, eleveId: Int): ApiResult<PaiementsResponse> {
        return try {
            val response = RetrofitClient.apiService.getPaiements(token, eleveId)
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                ApiResult.Error("Erreur lors du chargement des paiements (${response.code()}).")
            }
        } catch (e: Exception) {
            ApiResult.Error("Connexion impossible : ${e.message}")
        }
    }

    suspend fun getAbsences(token: String, eleveId: Int): ApiResult<AbsencesResponse> {
        return try {
            val response = RetrofitClient.apiService.getAbsences(token, eleveId)
            if (response.isSuccessful && response.body() != null) {
                ApiResult.Success(response.body()!!)
            } else {
                ApiResult.Error("Erreur lors du chargement des absences (${response.code()}).")
            }
        } catch (e: Exception) {
            ApiResult.Error("Connexion impossible : ${e.message}")
        }
    }
}
