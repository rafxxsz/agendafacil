<div class="form-group">
    <label class="label" for="name">Nome do serviço</label>
    <input class="input" type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required>
    @error('name') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="label" for="description">Descrição</label>
    <textarea class="textarea" id="description" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="row">
    <div class="form-group" style="flex:1;min-width:200px">
        <label class="label" for="duration_minutes">Duração (minutos)</label>
        <input class="input" type="number" id="duration_minutes" name="duration_minutes"
               value="{{ old('duration_minutes', $service->duration_minutes) }}" min="5" max="480" required>
        @error('duration_minutes') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group" style="flex:1;min-width:200px">
        <label class="label" for="price">Preço (R$)</label>
        <input class="input" type="number" step="0.01" id="price" name="price"
               value="{{ old('price', $service->price) }}" min="0" required>
        @error('price') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>

<label class="row" style="font-size:.95rem;margin-bottom:1rem">
    <input type="checkbox" name="active" value="1" @checked(old('active', $service->active ?? true))>
    Serviço ativo (visível para clientes)
</label>
