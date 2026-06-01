# validation.md

Owner: Susank Shakya

<aside>
✅

**`docs/backend-engine/validation.md`** · how the backend achieves “Rust-like” safety while staying in Laravel (ADR 0013).

</aside>

# 1. Purpose & scope

This page defines the **type-safety and validation strategy** that lets Nuvia Beauty stay in Laravel while approximating the guarantees that would otherwise motivate Rust. It moves whole classes of errors to compile/CI time and enforces zero-trust authorization on every action.

# 2. Layers

| Layer | Control |
| --- | --- |
| Static analysis | Larastan / PHPStan at **max** level in CI. |
| Strict types | `declare(strict_types=1)` across the codebase. |
| Boundaries | DTOs + Enums model all inputs/outputs; no loose arrays cross module boundaries. |
| Request validation | Strict Form Requests validate every endpoint payload. |
| Authorization | Policies gate every action (zero-trust, ADR 0012). |

# 3. Example boundary

```php
declare(strict_types=1);

final class AnalyzeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'object_key' => ['required', 'string'],
            'consent_tier' => ['required', new Enum(ConsentTier::class)],
        ];
    }
}
```

The validated payload is mapped to a DTO before any domain logic runs; domain services never receive raw arrays.

# 4. Why

These layers catch errors at compile/CI time, approximating the guarantees that would otherwise motivate Rust — without the development cost. Rust is reserved for isolated, internal, compute-heavy services later (ADR 0013).

# 5. Definition of done

- PHPStan max passes, no baseline regressions.
- Every endpoint has a Form Request and a Policy.
- Boundary types are DTOs/Enums, not arrays.
- `declare(strict_types=1)` present in all new files.

# 6. Related documentation

- API: [[api-contracts.md](http://api-contracts.md)](api-contracts%20md%209468ad6610754cc38b2fff1900887999.md). Guardrails: [](Untitled%20fa51edbb55544e499332c91e75809fb1.md). Decisions: ADR 0012, 0013, 0015.