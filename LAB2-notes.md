# LAB 2 — Form Request + Status Codes + API Resource

## Objectif

Améliorer l’API `Blueprint` en ajoutant :

* une validation propre avec `StoreBlueprintRequest`
* un endpoint de création `POST /api/blueprints`
* un status HTTP `201 Created` après création
* un status HTTP `422 Unprocessable Content` en cas d’erreur de validation
* une réponse JSON filtrée avec `BlueprintResource`

---

## Route ajoutée

La route suivante a été ajoutée dans `routes/api.php` :

```php
Route::post('/blueprints', [BlueprintController::class, 'store']);
```

Elle est placée dans le groupe protégé par `auth:sanctum`.

URL réelle :

```txt
POST /api/blueprints
```

---

## Form Request

Le fichier utilisé est :

```txt
app/Http/Requests/StoreBlueprintRequest.php
```

Règles de validation :

```php
public function rules(): array
{
    return [
        'nom' => ['required', 'string', 'max:100'],
        'ton' => ['required', 'string', 'max:255'],
        'max_hashtags' => ['required', 'integer', 'min:0', 'max:10'],
        'max_caracteres' => ['required', 'integer', 'min:50', 'max:280'],
    ];
}
```

## Pourquoi utiliser une Form Request ?

La Form Request permet de valider les données avant d’entrer dans la logique du controller.

Cela évite :

* les erreurs SQL
* les données invalides en base
* la logique de validation mélangée avec la logique métier

---

## Controller — Méthode store

Code final :

```php
public function store(StoreBlueprintRequest $request): JsonResponse
{
    $blueprint = auth()->user()->blueprints()->create($request->validated());

    return (new BlueprintResource($blueprint))
        ->response()
        ->setStatusCode(201);
}
```

Explication :

```php
$request->validated()
```

retourne uniquement les données validées.

```php
auth()->user()->blueprints()->create(...)
```

crée un Blueprint rattaché à l’utilisateur connecté.

```php
setStatusCode(201)
```

indique que la ressource a été créée avec succès.

---

## API Resource

Le fichier utilisé est :

```txt
app/Http/Resources/BlueprintResource.php
```

Code final :

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'nom' => $this->nom,
        'ton' => $this->ton,
        'max_hashtags' => $this->max_hashtags,
        'max_caracteres' => $this->max_caracteres,
    ];
}
```

## Pourquoi utiliser une API Resource ?

Avant la Resource, l’API retournait le modèle brut avec :

* `user_id`
* `created_at`
* `updated_at`

Avec `BlueprintResource`, on contrôle exactement les champs visibles dans la réponse JSON.

---

## Test GET /api/blueprints

Résultat obtenu :

```json
{
  "data": [
    {
      "id": 3,
      "nom": "Laravel Tips",
      "ton": "educational and concise",
      "max_hashtags": 1,
      "max_caracteres": 280
    }
  ]
}
```

Conclusion :

```txt
La liste des Blueprints est maintenant filtrée par BlueprintResource.
```

---

## Test GET /api/blueprints/3

Résultat obtenu :

```json
{
  "data": {
    "id": 3,
    "nom": "Laravel Tips",
    "ton": "educational and concise",
    "max_hashtags": 1,
    "max_caracteres": 280
  }
}
```

Conclusion :

```txt
La route show retourne aussi une réponse filtrée.
```

---

## Test POST /api/blueprints valide

Body envoyé :

```json
{
  "nom": "API Resource Test",
  "ton": "clear and practical",
  "max_hashtags": 1,
  "max_caracteres": 280
}
```

Résultat obtenu :

```http
HTTP/1.1 201 Created
```

Réponse JSON :

```json
{
  "data": {
    "id": 4,
    "nom": "API Resource Test",
    "ton": "clear and practical",
    "max_hashtags": 1,
    "max_caracteres": 280
  }
}
```

Conclusion :

```txt
Une donnée valide crée un Blueprint et retourne 201 Created.
```

---

## Test POST /api/blueprints invalide

Body envoyé :

```json
{
  "nom": "",
  "ton": "x",
  "max_hashtags": 50,
  "max_caracteres": 500
}
```

Résultat obtenu :

```http
HTTP/1.1 422 Unprocessable Content
```

Réponse JSON :

```json
{
  "message": "The nom field is required. (and 2 more errors)",
  "errors": {
    "nom": ["The nom field is required."],
    "max_hashtags": ["The max hashtags field must not be greater than 10."],
    "max_caracteres": ["The max caracteres field must not be greater than 280."]
  }
}
```

Conclusion :

```txt
Une donnée invalide est bloquée par StoreBlueprintRequest avant l’insertion en base.
```

---

## Conclusion du LAB 2

Le LAB 2 est validé parce que :

* `POST /api/blueprints` existe
* la route est protégée par `auth:sanctum`
* la validation est gérée par `StoreBlueprintRequest`
* une création valide retourne `201 Created`
* une requête invalide retourne `422 Unprocessable Content`
* les réponses sont filtrées avec `BlueprintResource`
* les champs internes ne sont plus exposés
