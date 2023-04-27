// Affiche un popup pour confirmer la suppression d'un enseignant
function confirmDelete(teacherId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer l'enseignant?") === true) {
        window.location.href = window.location.href + '?idTeacher=' + teacherId;
   
    }
}

