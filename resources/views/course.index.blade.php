<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
</head>
<body style="font-family: Poppins, sans-serif; margin: 0; background: #f8fafc; color: #0f172a;">

    <div style="max-width: 1100px; margin: 0 auto; padding: 24px 16px;">
        <h1 style="margin-top: 0;">Liste des Cours</h1>
        <div class="table-scroll panel" style="border-radius: 18px; padding: 8px;">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Enseignant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->titre }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->enseignant->nom }}</td>
                            <td>
                                <div class="stack-mobile">
                                    <a href="{{ route('courses.show', $course->id) }}">Voir</a>
                                    <a href="{{ route('courses.edit', $course->id) }}">Modifier</a>
                                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
