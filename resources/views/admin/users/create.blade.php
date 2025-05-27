@extends("layouts.app")

@section("title", "Ajouter un Utilisateur")

@section("content")
<div class="container">
    <h1>Ajouter un Nouvel Utilisateur</h1>

    <form action="{{ route("admin.users.store") }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control @error("name") is-invalid @enderror" id="name" name="name" value="{{ old("name") }}" required>
            @error("name")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ old("email") }}" required>
            @error("email")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control @error("password") is-invalid @enderror" id="password" name="password" required>
            @error("password")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="mb-3">
            <label for="roles" class="form-label">Rôles</label>
            <select multiple class="form-control @error("roles") is-invalid @enderror" id="roles" name="roles[]" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ (collect(old("roles"))->contains($role)) ? "selected" : "" }}>{{ $role }}</option>
                @endforeach
            </select>
             @error("roles")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Champ Type Stagiaire (conditionnel) --}}
        <div class="mb-3" id="stagiaire_type_div" style="display: none;"> {{-- Caché par défaut --}}
            <label for="type" class="form-label">Type de Stagiaire</label>
            <select class="form-control @error("type") is-invalid @enderror" id="type" name="type">
                <option value="" {{ old("type") == "" ? "selected" : "" }}>Sélectionner...</option>
                <option value="diplômé" {{ old("type") == "diplômé" ? "selected" : "" }}>Diplômé</option>
                <option value="en_cours_de_formation" {{ old("type") == "en_cours_de_formation" ? "selected" : "" }}>En cours de formation</option>
            </select>
            @error("type")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
        <a href="{{ route("admin.users.index") }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

{{-- Script simple pour afficher/cacher le type de stagiaire --}}
<script>
    document.getElementById("roles").addEventListener("change", function() {
        var selectedRoles = Array.from(this.selectedOptions).map(option => option.value);
        var typeDiv = document.getElementById("stagiaire_type_div");
        if (selectedRoles.includes("Stagiaire")) {
            typeDiv.style.display = "block";
        } else {
            typeDiv.style.display = "none";
            document.getElementById("type").value = ""; // Reset type if Stagiaire role is removed
        }
    });
    // Trigger change on page load if Stagiaire is pre-selected (e.g., validation error)
    document.getElementById("roles").dispatchEvent(new Event("change"));
</script>

@endsection

