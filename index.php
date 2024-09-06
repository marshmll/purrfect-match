<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purrfect Match | Encontre um amiguinho</title>
    <link rel="stylesheet" href="public/assets/styles/reset.css" />
    <link rel="stylesheet" href="public/assets/styles/components/components.css" />
    <link rel="stylesheet" href="public/assets/styles/pages/index.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
</head>

<body>
    <header class="header">
        <button class="header__search">
            <span class="material-symbols-outlined">search</span>
        </button>
        <div class="header__main">
            <div class="header__icon"></div>
            <h1 class="header__title">Purrfect Match</h1>
        </div>
        <button class="header__settings">
            <span class="material-symbols-outlined">tune</span>
        </button>
    </header>
    <nav class="navbar">
        <ul class="navbar__list">
            <li class="item">
                <a class="item__link active" href="/" title="Home">
                    <span class="item__icon material-symbols-outlined fill">home</span>
                    <span class="item__text">Home</span>
                </a>
            </li>
            <li class="item">
                <a class="item__link" href="/" title="Favoritos">
                    <span class="item__icon material-symbols-outlined">favorite</span>
                    <span class="item__text">Favoritos</span>
                </a>
            </li>
            <li class="item">
                <a class="item__link" href="/" title="Adoção">
                    <span class="item__icon material-symbols-outlined">search</span>
                    <span class="item__text">Adoção</span>
                </a>
            </li>
            <li class="item">
                <a class="item__link" href="/" title="Doar">
                    <span class="item__icon material-symbols-outlined">attach_money</span>
                    <span class="item__text">Doar</span>
                </a>
            </li>
            <li class="item">
                <a class="item__link" href="/" title="Perfil">
                    <span class="item__icon material-symbols-outlined">person</span>
                    <span class="item__text">Perfil</span>
                </a>
            </li>
            <li class="item">
                <a class="item__link" href="/" title="Sair">
                    <span class="item__icon material-symbols-outlined">logout</span>
                    <span class="item__text">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <main class="main">
        <h2 class="main__title">Gatinhos para você</h2>
        <section class="cards">
            <article class="card">
                <!-- Use inline css to fetch images with PHP -->
                <a class="card__image" href="/" style="background-image: url('https://images.ctfassets.net/ub3bwfd53mwy/5WFv6lEUb1e6kWeP06CLXr/acd328417f24786af98b1750d90813de/4_Image.jpg?w=750');"></a>
                <button class="card__fav" title="Adicionar aos favoritos">
                    <span class="item__icon material-symbols-outlined">favorite</span>
                </button>
                <div class="card__text">
                    <h3 class="card__name">Milo</h3>
                    <span class="card__age">2 anos</span>
                </div>
                <div class="card__personalities">
                    <div class="card__personality">Animado</div>
                    <div class="card__personality">Enérgico</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                </div>
            </article>
            <article class="card">
                <!-- Use inline css to fetch images with PHP -->
                <a class="card__image" href="/" style="background-image: url('https://images.ctfassets.net/ub3bwfd53mwy/5WFv6lEUb1e6kWeP06CLXr/acd328417f24786af98b1750d90813de/4_Image.jpg?w=750');"></a>
                <button class="card__fav" title="Adicionar aos favoritos">
                    <span class="item__icon material-symbols-outlined">favorite</span>
                </button>
                <div class="card__text">
                    <h3 class="card__name">Milo</h3>
                    <span class="card__age">2 anos</span>
                </div>
                <div class="card__personalities">
                    <div class="card__personality">Animado</div>
                    <div class="card__personality">Enérgico</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                </div>
            </article>
            <article class="card">
                <!-- Use inline css to fetch images with PHP -->
                <a class="card__image" href="/" style="background-image: url('https://images.ctfassets.net/ub3bwfd53mwy/5WFv6lEUb1e6kWeP06CLXr/acd328417f24786af98b1750d90813de/4_Image.jpg?w=750');"></a>
                <button class="card__fav" title="Adicionar aos favoritos">
                    <span class="item__icon material-symbols-outlined">favorite</span>
                </button>
                <div class="card__text">
                    <h3 class="card__name">Milo</h3>
                    <span class="card__age">2 anos</span>
                </div>
                <div class="card__personalities">
                    <div class="card__personality">Animado</div>
                    <div class="card__personality">Enérgico</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                    <div class="card__personality">Dócil</div>
                </div>
            </article>
        </section>
    </main>
</body>

</html>