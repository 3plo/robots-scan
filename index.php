<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.10.2016
 * Time: 17:44
 */

/**
 * Result
 */

define(OK, 'Ок');
define(ERROR, 'Ошибка');

/**
 * Task
 */

define(FILE_EXIST_TASK, 'Проверка наличия файла robots.txt');
define(DIRECTIVE_HOST_TASK, 'Проверка указания директивы Host');
define(DIRECTIVE_HOST_COUNT_TASK, 'Проверка количества директив Host, прописанных в файле');
define(FILE_SIZE_TASK, 'Проверка размера файла robots.txt');
define(DIRECTIVE_SITEMAP_TASK, 'Проверка указания директивы Sitemap');
define(FILE_HTTP_RESPONSE_TASK, 'Проверка кода ответа сервера для файла robots.txt');

/**
 * Positive recomendation
 */

define(RECOMENDATION_POSITIVE, 'Доработки не требуются');

/**
 * Positive status
 */

define(FILE_EXIST_STATUS_POSITIVE, 'Файл robots.txt присутствует');
define(DIRECTIVE_HOST_STATUS_POSITIVE, 'Директива Host указана');
define(DIRECTIVE_HOST_COUNT_STATUS_POSITIVE, 'В файле прописана 1 директива Host');
define(FILE_SIZE_STATUS_POSITIVE_BEGIN, 'Размер файла robots.txt составляет ');
define(FILE_SIZE_STATUS_POSITIVE_END, ' байт, что находится в пределах допустимой нормы');
define(DIRECTIVE_SITEMAP_STATUS_POSITIVE, 'Директива Sitemap указана');
define(FILE_HTTP_RESPONSE_STATUS_POSITIVE, 'Файл robots.txt отдаёт код ответа сервера 200');

/**
 * Negative recomendation
 */

define(FILE_EXIST_RECOMENDATION_NEGATIVE, 'Программист: Создать файл robots.txt и разместить его на сайте.');
define(DIRECTIVE_HOST_RECOMENDATIONS_NEGATIVE, 'Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.');
define(DIRECTIVE_HOST_COUNT_RECOMENDATION_NEGATIVE, 'Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта');
define(FILE_SIZE_RECOMENDATION_NEGATIVE, 'Программист: Максимально допустимый размер файла robots.txt составляем 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб');
define(DIRECTIVE_SITEMAP_RECOMENDATION_NEGATIVE, 'Программист: Добавить в файл robots.txt директиву Sitemap');
define(FILE_HTTP_RESPONSE_RECOMENDATION_NEGATIVE, 'Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200');

/**
 * Negative status
 */

define(FILE_EXIST_STATUS_NEGATIVE, 'Файл robots.txt отсутствует');
define(DIRECTIVE_HOST_STATUS_NEGATIVE, 'В файле robots.txt не указана директива Host');
define(DIRECTIVE_HOST_COUNT_STATUS_NEGATIVE, 'В файле прописано несколько директив Host');
define(FILE_SIZE_STATUS_NEGATIVE_BEGIN, 'Размера файла robots.txt составляет ');
define(FILE_SIZE_STATUS_NEGATIVE_END, ' байт, что превышает допустимую норму');
define(DIRECTIVE_SITEMAP_STATUS_NEGATIVE, 'В файле robots.txt не указана директива Sitemap');
define(FILE_HTTP_RESPONSE_STATUS_NEGATIVE, 'При обращении к файлу robots.txt сервер возвращает код ответа ');

if(isset($_POST['robots']))
{
    if($_POST['siteName'])
    {
        showForm();
        $fullURL = createFullURL($_POST['siteName']);
        showResult($fullURL);
        finishFile();
    }
    else
    {
        showForm();
        finishFile();
    }
}
else
{
    showForm();
    finishFile();
}

/**
 * method show form
 * require ./app/index.html
 */
function showForm()
{
    require_once "./app/index.html";
}

/**
 * method take final part of the html document
 */
function finishFile()
{
    echo <<<_ENDHTML
    </div>
</body>
</html>
_ENDHTML;
}

/**
 * @param string $url
 * @return string
 * method add protocol and file name to URL
 */
function createFullURL($url)
{
    $fileName = 'robots.txt';
    $protocolHTTP = 'http://';
    $protocolHTTPS = 'https://';
    $result = '';
    if(@substr_count($url, $fileName))
    {
        $result = $url;
    }
    else
    {
        if($url[@strlen($url) - 1] === '/')
        {
            $result = $url.$fileName;
        }
        else
        {
            $result .= $url.'/'.$fileName;
        }
    }
    if(!substr_count($url, $protocolHTTP) && !substr_count($url, $protocolHTTPS))
    {
        if(@file_get_contents($protocolHTTP.$result))
        {
            $result = $protocolHTTP.$result;
        }
        else if(@file_get_contents($protocolHTTPS.$result))
        {
            $result = $protocolHTTPS.$result;
        }
    }
    return $result;
}

/**
 * @param string $fullURL
 * method create HTML code with results
 */
function showResult($fullURL)
{
    $firstTask = FILE_EXIST_TASK;
    $secondTask = DIRECTIVE_HOST_TASK;
    $thirdTask = DIRECTIVE_HOST_COUNT_TASK;
    $fourthTask = FILE_SIZE_TASK;
    $fifthTask = DIRECTIVE_SITEMAP_TASK;
    $sixthTask = FILE_HTTP_RESPONSE_TASK;
    $result = makeResult($fullURL);
    echo <<< RESULT
        <div id="resultContainer">
            <table id="result">
                <tr id="firstTask">
                    <td class="task">
                        $firstTask
                    </td>
                    <td class="status">
                        {$result['task1']['result']}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task1']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task1']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="secondTask">
                    <td class="task">
                        $secondTask
                    </td>
                    <td class="status">
                        {$result['task2']['result']}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task2']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task2']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="thirdTask">
                    <td class="task">
                        $thirdTask
                    </td>
                    <td class="status">
                        {$result['task3']['result']}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task3']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task3']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="fourthTask">
                    <td class="task">
                        $fourthTask
                    </td>
                    <td class="status"> 
                        {$result['task4']['result']}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task4']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task4']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="fifthTask">
                    <td class="task">
                        $fifthTask
                    </td>
                    <td class="status">
                        {$result['task5']['result']}
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task5']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task5']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="sixthTask">
                    <td class="task">
                        $sixthTask
                    </td>
                    <td class="status">
                        {$result['task6']['result']}
                    </td>
                   <td>
                        <table>
                            <tr>
                                <td>
                                    Состояние
                                </td>
                                <td>
                                    {$result['task6']['status']}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Рекомендации
                                </td>
                                <td>
                                    {$result['task6']['recomendation']}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
RESULT;
}

/**
 * @param string $fullURL
 * @return array
 * method create array with analysis
 */
function makeResult($fullURL)
{
    $maxFileSize = 32*1024;
    $hostDirective = 'Host:';
    $sitemapDirective = 'Sitemap:';
    $result = [
        'task1' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ],
        'task2' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ],
        'task3' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ],
        'task4' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ],
        'task5' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ],
        'task6' => [
            'result' => '',
            'status' => '',
            'recomendation' => ''
        ]
    ];

    if(isRobotsExist($fullURL))
    {
        $result['task1']['result'] = OK;
        $result['task1']['status'] = FILE_EXIST_STATUS_POSITIVE;
        $result['task1']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task1']['result'] = ERROR;
        $result['task1']['status'] = FILE_EXIST_STATUS_NEGATIVE;
        $result['task1']['recomendation'] = FILE_EXIST_RECOMENDATION_NEGATIVE;
        return $result;
    }

    @$fileContent = file_get_contents($fullURL);

    if(isHostDirectiveExist($fileContent, $hostDirective))
    {
        $result['task2']['result'] = OK;
        $result['task2']['status'] = DIRECTIVE_HOST_STATUS_POSITIVE;
        $result['task2']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task2']['result'] = ERROR;
        $result['task2']['status'] = DIRECTIVE_HOST_STATUS_NEGATIVE;
        $result['task2']['recomendation'] = DIRECTIVE_HOST_RECOMENDATIONS_NEGATIVE;
    }

    if(checkCountHostDirective($fileContent) == 1)
    {
        $result['task3']['result'] = OK;
        $result['task3']['status'] = DIRECTIVE_HOST_COUNT_STATUS_POSITIVE;
        $result['task3']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task3']['result'] = ERROR;
        $result['task3']['status'] = DIRECTIVE_HOST_COUNT_RECOMENDATION_NEGATIVE;
        $result['task3']['recomendation'] = DIRECTIVE_HOST_COUNT_STATUS_NEGATIVE;
    }

    if(($fileSize = checkFileSize($fullURL)) <= $maxFileSize)
    {
        $result['task4']['result'] = OK;
        $result['task4']['status'] = FILE_SIZE_STATUS_POSITIVE_BEGIN.$fileSize.FILE_SIZE_STATUS_POSITIVE_END;
        $result['task4']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task4']['result'] = ERROR;
        $result['task4']['status'] = FILE_SIZE_STATUS_NEGATIVE_BEGIN.$fileSize.FILE_SIZE_STATUS_NEGATIVE_END ;
        $result['task4']['recomendation'] = DIRECTIVE_HOST_COUNT_RECOMENDATION_NEGATIVE;
    }

    if(isHostDirectiveExist($fileContent, $sitemapDirective))
    {
        $result['task5']['result'] = OK;
        $result['task5']['status'] = DIRECTIVE_SITEMAP_STATUS_POSITIVE;
        $result['task5']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task5']['result'] = ERROR;
        $result['task5']['status'] = DIRECTIVE_SITEMAP_STATUS_NEGATIVE;
        $result['task5']['recomendation'] = DIRECTIVE_SITEMAP_RECOMENDATION_NEGATIVE;
    }

    if(($responseCode = chechHTTPResponse($fullURL)) == '200')
    {
        $result['task6']['result'] = OK;
        $result['task6']['status'] = FILE_HTTP_RESPONSE_STATUS_POSITIVE;
        $result['task6']['recomendation'] = RECOMENDATION_POSITIVE;
    }
    else
    {
        $result['task6']['result'] = ERROR;
        $result['task6']['status'] = FILE_HTTP_RESPONSE_STATUS_NEGATIVE.$responseCode;
        $result['task6']['recomendation'] = FILE_HTTP_RESPONSE_RECOMENDATION_NEGATIVE;
    }

    return $result;
}

/**
 * @param string $fullURL
 * @return bool
 * method does check for the existence file in the site
 */
function isRobotsExist($fullURL)
{
    $result = false;
    if(@$robots = fopen($fullURL, 'r'))
    {
        $result = true;
    }
    return $result;
}

/**
 * @param string $fileContent
 * @param string $directive
 * @return bool
 * method makes checking for directives
 */
function isHostDirectiveExist($fileContent, $directive)
{
    $result = false;
    if(@strpos($fileContent, $directive) !== false)
    {
        $result = true;
    }
    return $result;
}

/**
 * @param string $fileContent
 * @return int
 * method makes checking the number of directives
 */
function checkCountHostDirective($fileContent)
{
    $directive = 'Host:';
    @$result = substr_count($fileContent, $directive);
    return $result;
}

/**
 * @param string $fullURL
 * @return int
 * method check size of file and return byte size
 */
function checkFileSize($fullURL)
{
    $fileName = 'file.txt';
    @@$file = file_put_contents($fileName, file_get_contents($fullURL));
    @$result = filesize($fileName);
    unlink($fileName);
    return $result;
}

/**
 * @param string $fullURL
 * @return string
 * method makes checking code for HTTP response
 */
function chechHTTPResponse($fullURL)
{
    @$header = get_headers($fullURL);;
    $result = $header[0][9].$header[0][10].$header[0][11];
    return $result;
}
?>