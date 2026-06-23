package com.schoolflow.app.data.model

data class LoginRequest(
    val email: String,
    val password: String
)

data class LoginResponse(
    val token: String,
    val user: UserDto
)

data class UserDto(
    val id: Int,
    val name: String,
    val email: String,
    val role: String,
    val eleve_id: Int?
)

data class EleveDto(
    val id: Int,
    val nom: String,
    val prenoms: String,
    val matricule: String,
    val classe: String,
    val photo: String?
)

data class DashboardResponse(
    val eleve: EleveDto,
    val trimestre: String,
    val moyenne_generale: Double?,
    val rang: Int,
    val total_eleves: Int,
    val dernieres_notes: List<NoteResumeDto>
)

data class NoteResumeDto(
    val matiere: String,
    val note: Double
)

data class NotesResponse(
    val trimestre: String,
    val notes: List<NoteDetailDto>
)

data class NoteDetailDto(
    val matiere: String,
    val coefficient: Int,
    val note: Double
)

data class PaiementsResponse(
    val paiements: List<PaiementDto>,
    val total_paye: Double
)

data class PaiementDto(
    val id: Int,
    val recu_numero: String,
    val montant: Double,
    val type: String,
    val trimestre: String,
    val date: String
)

data class ChangePasswordRequest(
    val current_password: String,
    val new_password: String,
    val new_password_confirmation: String
)

data class MessageResponse(
    val message: String
)

data class AbsencesResponse(
    val absences: List<AbsenceDto>,
    val nb_absences: Int,
    val nb_retards: Int,
    val nb_non_justifiees: Int
)

data class AbsenceDto(
    val id: Int,
    val date: String,
    val type: String,
    val justifiee: Boolean,
    val motif: String?
)
