<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\HolyStatement;
use App\Models\DailyBibleVerse;
use App\Models\BibleVerseTheme;

use DB;
use Cache;

class DailyBibleVerseImport implements ToCollection,WithHeadingRow,WithValidation,WithChunkReading
{

    use Importable;
    protected $importResult;

    public function __construct()
    {

    }
    public function collection(Collection $rows)
    {  
        try{
            DB::beginTransaction();
            
            $processedRows = 0;
            $totalRows = $rows->count();

            $previousTheme = null;

            foreach ($rows as $key=>$row) {

                $theme = $row['themes'] ?? null;

                if(empty($theme)) {
                    $theme = $previousTheme;
                }else {
                    $previousTheme = $theme;
                }

                $theme_data=BibleVerseTheme::where('name',$theme)->first();

                if(!$theme_data){

                    $inputData = ['name'=>$theme];
                    $theme_data = BibleVerseTheme::create($inputData);
                }

                $book_id = $row['book_id'];
                $chapter_no = $row['chapter_no'];
                $verse_no = $row['verse_no'];
                        
                $book = Book::find($book_id);

                if($book){
                    $chapter = Chapter::where('book_id',$book_id)
                                        ->where('chapter_no',$chapter_no)->first();
                    if($chapter){               
                        $verse = HolyStatement::where('book_id',$book_id)
                                            ->where('chapter_id',$chapter['chapter_id'])
                                            ->where('statement_no',$verse_no)->first();
                        if($verse){ 

                            $bible_verse_details['bible_id']        = $book->testament->bible_id;
                            $bible_verse_details['testament_id']    = $book->testament->testament_id;
                            $bible_verse_details['book_id']         = $book_id;
                            $bible_verse_details['chapter_id']      = $chapter['chapter_id'];
                            $bible_verse_details['verse_id']        = $verse['statement_id'];
                            $bible_verse_details['date']            = null;
                            $bible_verse_details['theme_id']        = $theme_data['id'];

                            $NewBibleVerse = DailyBibleVerse::create($bible_verse_details);
                        }else{
                            throw new \Exception("Chapter No - Verse No mismatch in Row-".$key+2);
                        }
                    }else{
                        throw new \Exception("BookId - Chapter No mismatch in Row-".$key+2);
                    }
                }else{
                    throw new \Exception("Unidentified Book Id in Row-".$key+2);
                }

                $processedRows++;
                $progress = ceil(($processedRows / $totalRows) * 100);
                Cache::put('import_progress_bible', $progress, now()->addSeconds(2));  
                
            }
            DB::commit();
            $return['result'] = "Success";
            $return['message'] = "successfully imported";
        }catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = "Failed due to " . $e->getMessage();
            if (isset($key)) {
                $errorMessage .= " at row " . ($key + 2);
            }
            $return['result'] = "Failed";
            $return['message'] = $errorMessage;
        }

        $this->setImportResult($return);

    }

    public function setImportResult($return)
    {
        $this->importResult = $return;
    }

    public function getImportResult()
    {
        return $this->importResult;
    }  

    public function rules(): array
    {
        return [
           'book_id'   => 'required',
           'chapter_no'=> 'required',           
           'verse_no'  => 'required',           
        ];
    }
    public function customValidationMessages()
    {
        return [
            'book_id.required'      => 'Book Id should not be empty',
            'chapter_no.required'   => 'Chapter No should not be empty',
            'verse_no.required'     => 'Verse No should not be empty',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}