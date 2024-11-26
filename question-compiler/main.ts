import * as XLSX from "https://unpkg.com/xlsx@0.18.5/xlsx.mjs";

type OverviewSchema = {
  id: string,
  title_nl: string,
  title_en: string,
  type: "numeric" | "select_one" | null
}

// Available sheet ids
const SHEET_OVERVIEW = "question_overview"
const SHEET_NUMERIC = "question_numeric"



const overviewObject : OverviewSchema = {
  id: "",
  title_nl: "",
  title_en: "",
  type: null
}

// Remaps column titles to column ids.
const REMAP = new Map<string,string>()
  .set("Question Identifier", "id")
  .set("Question Title (NL)", "title_nl")
  .set("Question Title (EN)", "title_en")
  .set("Question Type", "type")

// Read the workbook
const workbook = XLSX.readFileSync('questions.xlsx')

function sheetRemapping(sheet: {}[], template: object) : {}[] {
  return sheet.map((r : any) => {
    Object.keys(r).forEach(key => {
      if( REMAP.has(key) ) {
        r[REMAP.get(key)!] = r[key]
        delete r[key]
      }
    })
  
    return r
  }).map((r : any) => {
    Object.keys(r).forEach(s => {
      if (! (s in overviewObject)) {
        delete r[s]
      }
    })
  
    return {...overviewObject, ...r}
  })
}


// Remapping:
const question_overview = sheetRemapping(XLSX.utils.sheet_to_json(workbook.Sheets[SHEET_OVERVIEW]), overviewObject)
// const question_numeric = sheetRemapping(XLSX.utils.sheet_to_json(workbook.Sheets[SHEET_NUMERIC]))


console.log(question_overview)