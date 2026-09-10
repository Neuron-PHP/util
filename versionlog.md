## 0.6.12

## 0.6.11 2026-09-10

## 0.7.0 2025-12-11
* **WebHook refactored to use system abstractions** - Now uses `IHttpClient` interface instead of direct curl calls
* Added `neuron-php/core` 0.8.* dependency for system abstractions
* WebHook now supports dependency injection with optional `IHttpClient` parameter
* Maintains full backward compatibility - existing code works without changes
* Tests rewritten to use `MemoryHttpClient` for deterministic, fast testing without network dependencies
* Timeout still defaults to 10 seconds as before

## 0.6.9 2026-01-13

## 0.6.8 2025-02-03
* Added a timeout to webhook.

## 0.6.7 2025-01-12
## 0.6.6 2025-01-12
## 0.6.5
## 0.6.4
## 0.6.3
## 0.6.2 2024-12-16
* Updated core packages.

## 0.6.1 2023-01-13
* Added the env class.

## 0.5.2
* Made IRunnable argv default to empty array.

## 0.5.1
