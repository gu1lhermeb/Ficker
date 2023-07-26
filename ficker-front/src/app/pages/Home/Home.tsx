import Image from "next/image";
import styles from "./home.module.scss";
import Link from "next/link";

export const HomeScreen = () => {
  return (
    <div className={styles.container}>
      <div style={{ background: "#fff", padding: 10, alignItems: "center" }}>
        <Image src="/logo.png" alt="Logo" width={130} height={27} />
      </div>
      <div className={styles.contentContainer}>
        <Image
          src="/managemoney.png"
          alt="Items de finanças"
          width={400}
          height={267}
          className={styles.image}
        />
        <div>
          <p>
            Assuma o controle de suas finanças com o Ficker, a solução completa
            para gerenciar suas despesas.
          </p>
          <div className={styles.buttonContainer}>
            <Link href={"/login"}>
              <button className={styles.whiteButton}>Entrar</button>
            </Link>
            <button className={styles.purpleButton}>Cadastrar-se</button>
          </div>
        </div>
      </div>
    </div>
  );
};
