import { Col, Row } from "antd";
import Typography from "antd/es/typography";
import dayjs from "dayjs";
import Image from "next/image";

interface Card {
  best_day: number;
  created_at: Date;
  description: string;
  expiration: number;
  flag_id: number;
  id: number;
  updated_at: Date;
  user_id: number;
}

interface CardProps {
  card: Card;
  totalValue?: number;
}
export const CardInformation = ({ card, totalValue }: CardProps) => {
  const { Text, Title } = Typography;

  const showDate = (date: number) => {
    if (date < dayjs().date()) {
      return dayjs().month() + 2;
    }
    return dayjs().month() + 1;
  };

  return (
    <Col
      style={{
        padding: 20,
        background: "#fff",
        borderRadius: 8,
        margin: 20,
        boxShadow: "0px 1px 2px 2px rgba(0,0,0,0.1)",
        cursor: "pointer",
      }}
      lg={21}
      xs={20}
      // onClick={() => setSelectedCard(card)}
    >
      <div>
        <Row align={"middle"} style={{ marginBottom: 15 }}>
          {card.flag_id === 1 ? (
            <Image src={"/mastercard.png"} alt="Logo" width={39} height={30} />
          ) : (
            <Image src={"/visa.png"} alt="Logo" width={39} height={12} />
          )}
          <Text type="secondary" style={{ marginLeft: 10 }}>
            {card.description}
          </Text>
        </Row>
        <Col>
          <Text type="secondary">Próxima fatura:</Text>
          <Title level={4}>R${totalValue?.toString()}</Title>
        </Col>
        <Row justify={"end"}>
          <Col>
            <Col>
              <Text type="secondary">Próxima fatura:</Text>
            </Col>
            <Row justify={"end"}>
              <Col>
                <Text type="secondary">
                  {card.expiration}/
                  {showDate(card.expiration) < 10
                    ? "0" + showDate(card.expiration)
                    : showDate(card.expiration)}
                </Text>
              </Col>
            </Row>
          </Col>
        </Row>
      </div>
    </Col>
  );
};
