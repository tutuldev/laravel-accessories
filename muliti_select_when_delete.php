   public function edit($id)
    {

        $company = Company::firstWhere('id',$id);

        // return $company->industry;


        $jobindustries = JobIndustry::get();
        return view('userend.company.edit', compact('company','jobindustries'));
    }
	
	
	
	
								<div class="col-md-6">
                                    <label for="google_map">Job industry</label>
                                    <select name="job_industry[]"
                                    class="js-example-basic-multiple form-select" multiple="multiple"
                                    data-width="100%">

                                    @foreach ($jobindustries as $jobindustry)
                                        <option value="{{ $jobindustry->id }}"
                                            @if (!empty($company->industry) && in_array($jobindustry->id, json_decode($company->industry)) ) selected @endif>
                                            {{ $jobindustry->name }}</option>
                                    @endforeach

                                </select>
                                </div>