# LAB 1 — Routes API + Controller + réponse JSON

## Objectif

Créer les premières routes REST de la ressource `Blueprint` dans l’API ThreadForge.

L’objectif de ce LAB est de comprendre le flux de base d’une API Laravel :

```txt
Requête HTTP
    ↓
Route API
    ↓
Controller
    ↓
Eloquent Model
    ↓
Réponse JSON
```

---

## Routes implémentées

Les routes sont déclarées dans `routes/api.php`.

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/blueprints', [BlueprintController::class, 'index']);
    Route::get('/blueprints/{blueprint}', [BlueprintController::class, 'show']);
});
```

Laravel ajoute automatiquement le préfixe `/api`.

Donc les vraies URLs sont :

```txt
GET /api/blueprints
GET /api/blueprints/{blueprint}
```

---

## Authentification

Les routes `blueprints` sont protégées par le middleware :

```php
auth:sanctum
```

Cela signifie qu’un utilisateur doit envoyer un Bearer Token valide pour accéder aux Blueprints.

---

## Test sans Bearer Token

Commande utilisée :

```powershell
curl.exe -i http://127.0.0.1:8000/api/blueprints
```

Résultat attendu :

```http
HTTP/1.1 401 Unauthorized
```

Réponse JSON :

```json
{
  "message": "Unauthenticated."
}
```

Conclusion :

```txt
Sans token valide, l’API refuse l’accès.
```

---

## Test avec Bearer Token

Commande utilisée :

```powershell
curl.exe -i http://127.0.0.1:8000/api/blueprints `
  -H "Accept: application/json" `
  -H "Authorization: Bearer TOKEN_HERE"
```

Résultat attendu :

```http
HTTP/1.1 200 OK
```

Conclusion :

```txt
Avec un token valide, Sanctum authentifie l’utilisateur et la route devient accessible.
```

---

## Controller — Méthode index

Code utilisé :

```php
public function index(): JsonResponse
{
    $blueprints = auth()->user()->blueprints()->latest()->get();

    return response()->json($blueprints);
}
```

Explication :

```php
auth()->user()
```

récupère l’utilisateur connecté grâce au Bearer Token.

```php
blueprints()
```

utilise la relation entre `User` et `Blueprint`.

```php
latest()
```

trie les blueprints du plus récent au plus ancien.

```php
get()
```

exécute la requête SQL et récupère les résultats.

```php
response()->json($blueprints)
```

retourne les données au format JSON.

---

## Test GET /api/blueprints

Résultat obtenu :

```json
[
  {
    "id": 2,
    "user_id": 1,
    "nom": "Tech Twitter Style",
    "ton": "professional but relaxed",
    "max_hashtags": 1,
    "max_caracteres": 280,
    "created_at": "2026-06-29T11:13:38.000000Z",
    "updated_at": "2026-06-29T11:13:38.000000Z"
  },
  {
    "id": 1,
    "user_id": 1,
    "nom": "Tech Twitter Style",
    "ton": "professional but relaxed",
    "max_hashtags": 1,
    "max_caracteres": 280,
    "created_at": "2026-06-29T11:10:45.000000Z",
    "updated_at": "2026-06-29T11:10:45.000000Z"
  }
]
```

Conclusion :

```txt
La route retourne bien les blueprints de l’utilisateur connecté.
```

---

## Controller — Méthode show

Code utilisé :

```php
public function show(Blueprint $blueprint): JsonResponse
{
    return response()->json($blueprint);
}
```

Laravel utilise ici le Route Model Binding.

Exemple :

```txt
GET /api/blueprints/1
```

Laravel transforme automatiquement `{blueprint}` en modèle `Blueprint`.

C’est similaire à :

```php
Blueprint::findOrFail(1);
```

---

## Test GET /api/blueprints/1 avec Bearer Token

Commande utilisée :

```powershell
curl.exe -i http://127.0.0.1:8000/api/blueprints/1 `
  -H "Accept: application/json" `
  -H "Authorization: Bearer TOKEN_HERE"
```

Résultat attendu :

```http
HTTP/1.1 200 OK
```

Réponse JSON :

```json
{
  "id": 1,
  "user_id": 1,
  "nom": "Tech Twitter Style",
  "ton": "professional but relaxed",
  "max_hashtags": 1,
  "max_caracteres": 280,
  "created_at": "2026-06-29T11:10:45.000000Z",
  "updated_at": "2026-06-29T11:10:45.000000Z"
}
```

Conclusion :

```txt
La route retourne bien un seul Blueprint existant.
```

---

## Test GET /api/blueprints/1 sans Bearer Token

Commande utilisée :

```powershell
curl.exe -i http://127.0.0.1:8000/api/blueprints/1
```

Résultat attendu :

```http
HTTP/1.1 401 Unauthorized
```

Réponse JSON :

```json
{
  "message": "Unauthenticated."
}
```

Conclusion :

```txt
Même la route show est protégée par Sanctum.
```

---

## Problème observé

Pour l’instant, le controller retourne directement les modèles Eloquent :

```php
return response()->json($blueprints);
```

ou :

```php
return response()->json($blueprint);
```

Cela expose des champs internes :

```txt
user_id
created_at
updated_at
```

Ce n’est pas idéal pour une API professionnelle.

---

## Correction prévue dans LAB 2

Dans le LAB 2, ce problème sera corrigé avec une API Resource :

```php
BlueprintResource
```

L’API Resource permettra de choisir exactement les champs visibles dans la réponse JSON.

Exemple attendu plus tard :

```json
{
  "id": 1,
  "nom": "Tech Twitter Style",
  "ton": "professional but relaxed",
  "max_hashtags": 1,
  "max_caracteres": 280
}
```

Sans exposer :

```txt
user_id
created_at
updated_at
```

---

## Conclusion du LAB 1

Le LAB 1 est validé parce que :

* les routes API sont déclarées dans `routes/api.php`
* les routes retournent du JSON
* les routes sont protégées par `auth:sanctum`
* une requête sans token retourne `401 Unauthorized`
* une requête avec token retourne `200 OK`
* `GET /api/blueprints` retourne la liste des blueprints
* `GET /api/blueprints/{blueprint}` retourne un blueprint précis
* le problème des modèles bruts a été identifié pour le LAB 2
