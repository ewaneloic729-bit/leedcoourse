<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de reinitialisation</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:560px;background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#16a34a,#14532d);padding:18px 24px;color:#ffffff;">
                            <h1 style="margin:0;font-size:20px;line-height:1.3;">LEEDCOURSE - Recuperation de compte</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px 0;font-size:15px;line-height:1.5;">Bonjour {{ $recipientName ?: 'Utilisateur' }},</p>
                            <p style="margin:0 0 14px 0;font-size:15px;line-height:1.5;">
                                Utilisez ce code temporaire pour reinitialiser votre mot de passe:
                            </p>
                            <div style="margin:0 0 16px 0;padding:14px;border:1px dashed #16a34a;border-radius:10px;background:#f0fdf4;text-align:center;">
                                <span style="font-size:30px;font-weight:800;letter-spacing:8px;color:#166534;">{{ $otpCode }}</span>
                            </div>
                            <p style="margin:0 0 10px 0;font-size:14px;line-height:1.5;color:#334155;">
                                Ce code expire dans {{ $expiresInMinutes }} minutes.
                            </p>
                            <p style="margin:0;font-size:14px;line-height:1.5;color:#334155;">
                                Si vous n'etes pas a l'origine de cette demande, ignorez simplement cet email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;color:#475569;font-size:12px;">
                            Message automatique - LEEDCOURSE
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

