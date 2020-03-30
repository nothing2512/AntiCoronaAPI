# Anti Corona RestFul API

This Restful API used For:

1. Monitoring COVID-19 in the world especially in Indonesia
2. COVID-19 Solutions

<br>
<br>

### Request & Responses

<br>
Get Cases In Country, it can only retrieve data from Indonesia

Link: [https://nothing-ar.000webhostapp.com/anticorona/v1/cases/indonesia](https://nothing-ar.000webhostapp.com/anticorona/v1/cases/indonesia)
```$xslt
{
  "location": "Indonesia",
  "cases": "1,414",
  "recovered": "75",
  "death": "122",
  "flag": "https://raw.githubusercontent.com/NovelCOVID/API/master/assets/flags/id.png"
}
```

<br>
Get Cases In Province, it can only retrieve data from Indonesia

Link: [https://nothing-ar.000webhostapp.com/anticorona/v1/cases/indonesia/province](https://nothing-ar.000webhostapp.com/anticorona/v1/cases/indonesia/province)
```$xslt
[
  ...
  {
    "location": "DKI Jakarta",
    "cases": 698,
    "recovered": 48,
    "death": 74
  },
  ...
]
```

<br>
Get Cases In All Countries

Link: [https://nothing-ar.000webhostapp.com/anticorona/v1/cases/list](https://nothing-ar.000webhostapp.com/anticorona/v1/cases/list)
```$xslt
[
  ...
  {
    "location": "Indonesia",
    "cases": "1,414",
    "recovered": "75",
    "death": "122",
    "flag": "https://raw.githubusercontent.com/NovelCOVID/API/master/assets/flags/id.png"
  },
  ...
]
```

<br>
Get Global Cases

Link: [https://nothing-ar.000webhostapp.com/anticorona/v1/cases/global](https://nothing-ar.000webhostapp.com/anticorona/v1/cases/global)
```$xslt
{
  "cases": 743081,
  "death": 35347,
  "recovered": 157046
}
```

<br>
Get Frequently Asked Question

Link: [https://nothing-ar.000webhostapp.com/anticorona/v1/faqs?lang=eng](https://nothing-ar.000webhostapp.com/anticorona/v1/faqs?lang=eng)
<br> 
Parameter: lang (String)
```$xslt
[
  {
    "question": "There are family members who show mild symptoms of the corona virus, how do I treat it at home?",
    "answer": "If a family member has a fever, fatigue or dry cough, seek help at a health facility...."
  }
]
```
<br></br>
<br></br>
### Source Data

[Kawal Corona](https://kawalcorona.com)<br>
[Corona Ninja](https://github.com/novelcovid/api)<br>
[COVID 19](https://www.covid19.go.id)



