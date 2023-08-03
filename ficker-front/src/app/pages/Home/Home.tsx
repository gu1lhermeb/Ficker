import Image from "next/image";
import styles from "./home.module.scss";
import Link from "next/link";

export const HomeScreen = () => {
  return (
    <div className={styles.container}>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Link href={"/"} style={{ background: "#fff", padding: 10, alignItems: "center" }}>
          <Image src="/logo.png" alt="Logo" width={130} height={27} />
        </Link>
      </div>
      <div className={styles.contentContainer}>
        <Image
          src="/managemoney.png"
          alt="Items de finanças"
          width={400}
          height={267}
          className={styles.image}
        />
        <div className={styles.introContainer}>
          <p>
            Assuma o controle de suas finanças com o Ficker, a solução completa para gerenciar suas despesas.
          </p>
          <div className={styles.buttonContainer}>
            <Link href={"/login"}>
              <button className={styles.whiteButton}>Entrar</button>
            </Link>
            <Link href={"/createaccount"}>
              <button className={styles.purpleButton}>Cadastrar</button>
            </Link>
          </div>
        </div>
      </div>
      <div className={styles.imagesContainer}>
        <div className={styles.grid}>
          <div className={styles.content}>
            <Image src="/news.png" alt="Logo" width={150} height={150} />
            <p>
              Obtenha informações valiosas sobre sua saúde financeira com os recursos abrangentes de
              relatórios.
            </p>
          </div>
          <div className={styles.content}>
            <Image src="/pigmoney.png" alt="Logo" width={150} height={150} />
            <p>Planeje e alcance suas metas financeiras com facilidade.</p>
          </div>
          <div className={styles.content}>
            <Image src="/financialman.png" alt="Logo" width={150} height={150} />
            <p>Acompanhe seus ganhos e assuma o controle de seu futuro.</p>
          </div>
          <div className={styles.content}>
            <Image src="/wallet.png" alt="Logo" width={150} height={150} />
            <p>Assuma o controle de seus gastos e fique por dentro do seu orçamento.</p>
          </div>
        </div>
      </div>
    </div>
  );
};