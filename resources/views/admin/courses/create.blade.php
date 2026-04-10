<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un cours | LEEDCOURSE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        :root {
            --brand: #16a34a;
            --brand-dark: #166534;
            --ink: #0f172a;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #e2e8f0;
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(2, 6, 23, 0.88), rgba(22, 101, 52, 0.66)),
                url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
            padding: 24px;
            display: grid;
            place-items: center;
        }

        .container {
            width: 100%;
            max-width: 950px;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 26px 60px rgba(2, 6, 23, 0.45);
            display: grid;
            grid-template-columns: 1.08fr 0.92fr;
            backdrop-filter: blur(8px);
            background: rgba(2, 6, 23, 0.45);
        }

        .left {
            padding: 48px;
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.48), rgba(3, 7, 18, 0.2));
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand {
            text-decoration: none;
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 0.14em;
            font-size: 0.95rem;
        }

        .hero-title {
            margin: 18px 0 12px;
            font-size: clamp(1.75rem, 2.5vw, 2.7rem);
            line-height: 1.14;
            color: #f8fafc;
        }

        .hero-text {
            margin: 0;
            color: #cbd5e1;
            max-width: 430px;
            line-height: 1.6;
        }

        .pill {
            width: fit-content;
            border-radius: 999px;
            border: 1px solid rgba(134, 239, 172, 0.45);
            background: rgba(22, 101, 52, 0.26);
            color: #dcfce7;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 8px 14px;
        }

        .right {
            background: #ffffff;
            color: var(--ink);
            padding: 36px;
        }

        .title {
            margin: 0;
            font-size: 1.7rem;
            font-weight: 800;
        }

        .subtitle {
            margin: 8px 0 18px;
            color: var(--muted);
            font-size: 0.94rem;
        }

        .alert {
            border-radius: 10px;
            padding: 11px 12px;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .field {
            margin-bottom: 12px;
        }

        .label {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input, .textarea, .select {
            width: 100%;
            border: 1px solid #d2dae5;
            border-radius: 12px;
            padding: 12px 13px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: inherit;
            background: #ffffff;
        }

        .textarea {
            min-height: 130px;
            resize: vertical;
        }

        .input:focus, .textarea:focus, .select:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.18);
        }

        .inline {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            background: #f8fafc;
            color: #334155;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 14px;
        }

        .inline input {
            margin: 0;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .thumb-preview {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dbe2ea;
            background: linear-gradient(135deg, #0f172a, #14532d);
            display: grid;
            place-items: center;
        }

        .thumb-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-placeholder {
            color: #dcfce7;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.92rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #ffffff;
            flex: 1;
            min-width: 170px;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #dbe2ea;
        }

        @media (max-width: 980px) {
            .container {
                grid-template-columns: 1fr;
            }

            .left {
                padding: 26px 24px;
                min-height: 210px;
            }

            .right {
                padding: 22px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <section class="left">
            <div>
                <a class="brand" href="{{ url('/') }}">LEEDCOURSE</a>
                <h1 class="hero-title">Publiez une formation professionnelle.</h1>
                <p class="hero-text">Renseignez les informations du cours, choisissez le niveau et rendez la formation disponible pour tous les apprenants de la plateforme.</p>
            </div>
            <span class="pill">Espace Formateur</span>
        </section>

        <section class="right">
            <h2 class="title">Créer un nouveau cours</h2>
            <p class="subtitle">Complétez les champs ci-dessous pour publier votre contenu.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ request()->routeIs('enseignant.courses.create') ? route('enseignant.courses.store') : route('admin.courses.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label class="label" for="title">Titre du cours</label>
                    <input id="title" class="input" type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="field">
                    <label class="label" for="description">Description</label>
                    <textarea id="description" class="textarea" name="description" required>{{ old('description') }}</textarea>
                </div>

                <div class="field">
                    <label class="label" for="category">Catégorie</label>
                    <input id="category" class="input" type="text" name="category" value="{{ old('category') }}" placeholder="Ex: Informatique, Réseau..." required>
                </div>

                <div class="field">
                    <label class="label" for="level">Niveau</label>
                    <select id="level" class="select" name="level">
                        <option value="">Sélectionner un niveau</option>
                        <option value="Debutant" @selected(old('level') === 'Debutant')>Débutant</option>
                        <option value="Intermediaire" @selected(old('level') === 'Intermediaire')>Intermédiaire</option>
                        <option value="Avance" @selected(old('level') === 'Avance')>Avancé</option>
                    </select>
                </div>

                <div class="field">
                    <label class="label" for="image">Miniature du cours</label>
                    <div class="thumb-preview" id="thumbnailPreview">
                        <span class="thumb-placeholder">Apercu miniature 16:9</span>
                    </div>
                    <input id="image" class="input" type="file" name="image" accept="image/png,image/jpeg,image/webp">
                    <p class="subtitle" style="margin-top:8px;">Format recommande: 1280x720 (JPG, PNG ou WEBP).</p>
                </div>

                <label class="inline">
                    <input type="checkbox" name="is_available" value="1" @checked(old('is_available'))>
                    Rendre ce cours disponible sur la plateforme
                </label>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Créer le cours</button>
                    <a class="btn btn-secondary" href="{{ route('dashboard.enseignant') }}">Retour dashboard</a>
                </div>
            </form>
        </section>
    </div>
    <script>
        const imageInput = document.getElementById('image');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        imageInput?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) {
                thumbnailPreview.innerHTML = '<span class="thumb-placeholder">Apercu miniature 16:9</span>';
                return;
            }

            const fileUrl = URL.createObjectURL(file);
            thumbnailPreview.innerHTML = '<img src=\"' + fileUrl + '\" alt=\"Apercu miniature\">';
        });
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
