<div class="panel">
    <h3 class="section-title">Taxonomy and range</h3>
    <div class="grid-two">
        @foreach(['kingdom' => 'Kingdom', 'animal_class' => 'Class', 'animal_order' => 'Order', 'family' => 'Family', 'genus' => 'Genus', 'species' => 'Species'] as $field => $label)
            <div class="field">
                <label for="{{ $field }}">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="text" value="{{ old($field, $animal?->$field) }}">
            </div>
        @endforeach
        @foreach(['geographical_range' => 'Geographical range', 'native_countries' => 'Native countries', 'distribution' => 'Distribution'] as $field => $label)
            <div class="field">
                <label for="{{ $field }}">{{ $label }}</label>
                <textarea id="{{ $field }}" name="{{ $field }}">{{ old($field, $animal?->$field) }}</textarea>
            </div>
        @endforeach
        <div class="field">
            <label for="habitat">Habitat description</label>
            <input id="habitat" name="habitat" type="text" value="{{ old('habitat', $animal?->habitat) }}">
        </div>
    </div>
</div>

<div class="panel">
    <h3 class="section-title">Animal facts and conservation</h3>
    <div class="grid-two">
        @foreach(['diet' => 'Diet', 'lifespan' => 'Lifespan', 'average_weight' => 'Average weight', 'average_height_length' => 'Average height / length', 'speed' => 'Speed'] as $field => $label)
            <div class="field">
                <label for="{{ $field }}">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="text" value="{{ old($field, $animal?->$field) }}">
            </div>
        @endforeach
        @foreach([
            'behaviour' => 'Behaviour',
            'reproduction' => 'Reproduction',
            'ecological_role' => 'Ecological role',
            'population_information' => 'Population information',
            'major_threats' => 'Major threats',
            'conservation_efforts' => 'Conservation efforts',
        ] as $field => $label)
            <div class="field">
                <label for="{{ $field }}">{{ $label }}</label>
                <textarea id="{{ $field }}" name="{{ $field }}">{{ old($field, $animal?->$field) }}</textarea>
            </div>
        @endforeach
        <div class="field">
            <label for="fun_fact">Fun fact</label>
            <textarea id="fun_fact" name="fun_fact">{{ old('fun_fact', $animal?->fun_fact) }}</textarea>
        </div>
    </div>
</div>

<div class="panel">
    <h3 class="section-title">Search and sharing</h3>
    <div class="grid-two">
        <div class="field">
            <label for="meta_title">Meta title</label>
            <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title', $animal?->meta_title) }}">
        </div>
        <div class="field">
            <label for="canonical_url">Canonical URL</label>
            <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url', $animal?->canonical_url) }}">
        </div>
        <div class="field">
            <label for="social_image">Social sharing image URL</label>
            <input id="social_image" name="social_image" type="url" value="{{ old('social_image', $animal?->social_image) }}">
        </div>
        <div class="field">
            <label for="meta_description">Meta description</label>
            <textarea id="meta_description" name="meta_description" maxlength="320">{{ old('meta_description', $animal?->meta_description) }}</textarea>
        </div>
    </div>
    <div class="field">
        <label><input type="hidden" name="noindex" value="0"><input type="checkbox" name="noindex" value="1" {{ old('noindex', $animal?->noindex ?? false) ? 'checked' : '' }}> Ask search engines not to index this page</label>
        <label><input type="hidden" name="nofollow" value="0"><input type="checkbox" name="nofollow" value="1" {{ old('nofollow', $animal?->nofollow ?? false) ? 'checked' : '' }}> Ask search engines not to follow links on this page</label>
    </div>
</div>

<div class="panel">
    <h3 class="section-title">Frequently asked questions</h3>
    @foreach($animal?->faqs ?? [] as $faq)
        <div class="grid-two">
            <div class="field">
                <label for="faq-question-{{ $faq->id }}">Question</label>
                <input id="faq-question-{{ $faq->id }}" name="faqs[{{ $faq->id }}][question]" value="{{ old('faqs.'.$faq->id.'.question', $faq->question) }}">
            </div>
            <div class="field">
                <label for="faq-answer-{{ $faq->id }}">Answer</label>
                <textarea id="faq-answer-{{ $faq->id }}" name="faqs[{{ $faq->id }}][answer]">{{ old('faqs.'.$faq->id.'.answer', $faq->answer) }}</textarea>
            </div>
            <div class="field">
                <label><input type="checkbox" name="faqs[{{ $faq->id }}][remove]" value="1"> Remove this FAQ</label>
            </div>
        </div>
    @endforeach
    @for($i = 0; $i < 3; $i++)
        <div class="grid-two">
            <div class="field">
                <label for="new-faq-question-{{ $i }}">New question</label>
                <input id="new-faq-question-{{ $i }}" name="new_faqs[{{ $i }}][question]" value="{{ old('new_faqs.'.$i.'.question') }}">
            </div>
            <div class="field">
                <label for="new-faq-answer-{{ $i }}">Answer</label>
                <textarea id="new-faq-answer-{{ $i }}" name="new_faqs[{{ $i }}][answer]">{{ old('new_faqs.'.$i.'.answer') }}</textarea>
            </div>
        </div>
    @endfor
</div>

<div class="panel">
    <h3 class="section-title">Image gallery</h3>
    @foreach($animal?->images ?? [] as $galleryImage)
        <div class="grid-two">
            @foreach(['alt_text' => 'Alt text', 'caption' => 'Caption', 'credit' => 'Image credit', 'source' => 'Image source'] as $field => $label)
                <div class="field">
                    <label for="image-{{ $galleryImage->id }}-{{ $field }}">{{ $label }}</label>
                    <input id="image-{{ $galleryImage->id }}-{{ $field }}" name="images[{{ $galleryImage->id }}][{{ $field }}]" value="{{ old('images.'.$galleryImage->id.'.'.$field, $galleryImage->$field) }}">
                </div>
            @endforeach
            <div class="field">
                <label><input type="checkbox" name="images[{{ $galleryImage->id }}][remove]" value="1"> Remove this image</label>
            </div>
        </div>
    @endforeach
    @for($i = 0; $i < 4; $i++)
        <div class="grid-two">
            <div class="field">
                <label for="new-gallery-image-{{ $i }}">Gallery image</label>
                <input id="new-gallery-image-{{ $i }}" name="new_gallery_images[{{ $i }}][file]" type="file" accept="image/*">
            </div>
            @foreach(['alt_text' => 'Alt text', 'caption' => 'Caption', 'credit' => 'Image credit', 'source' => 'Image source'] as $field => $label)
                <div class="field">
                    <label for="new-gallery-{{ $i }}-{{ $field }}">{{ $label }}</label>
                    <input id="new-gallery-{{ $i }}-{{ $field }}" name="new_gallery_images[{{ $i }}][{{ $field }}]" value="{{ old('new_gallery_images.'.$i.'.'.$field) }}">
                </div>
            @endforeach
        </div>
    @endfor
</div>

<div class="panel">
    <h3 class="section-title">Related animals</h3>
    <div class="field">
        <label for="related_animals">Select related animals</label>
        <select id="related_animals" name="related_animals[]" multiple>
            @foreach($allAnimals as $relatedAnimal)
                @if(!$animal || $animal->id !== $relatedAnimal->id)
                    <option value="{{ $relatedAnimal->id }}" {{ in_array($relatedAnimal->id, old('related_animals', $animal?->relatedAnimals->modelKeys() ?? [])) ? 'selected' : '' }}>{{ $relatedAnimal->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
